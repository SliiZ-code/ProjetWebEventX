#include "ApiClient.hpp"
#include <curl/curl.h>
#include <nlohmann/json.hpp>
#include <sstream>
#include <iostream>

using json = nlohmann::json;

struct WriteData {
    std::string data;
};

static size_t WriteCallback(void* contents, size_t size, size_t nmemb, WriteData* writeData) {
    size_t totalSize = size * nmemb;
    writeData->data.append(static_cast<char*>(contents), totalSize);
    return totalSize;
}

ApiClient::ApiClient(const std::string& baseUrl) : m_baseUrl(baseUrl), m_curl(curl_easy_init()) {
    if (!m_curl) {
        throw std::runtime_error("Failed to initialize CURL");
    }
}

ApiClient::~ApiClient() {
    if (m_curl) {
        curl_easy_cleanup(static_cast<CURL*>(m_curl));
    }
}

void ApiClient::setAuthToken(const std::string& token) {
    m_authToken = token;
}

std::string ApiClient::makeRequest(const std::string& endpoint, const std::string& method, const std::string& data) {
    CURL* curl = static_cast<CURL*>(m_curl);
    WriteData writeData;
    
    std::string url = m_baseUrl + endpoint;
    std::cout << "Making request to: " << url << " with method: " << method << std::endl;
    if (!data.empty()) {
        std::cout << "Data: " << data << std::endl;
    }
    
    curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteCallback);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, &writeData);
    curl_easy_setopt(curl, CURLOPT_TIMEOUT, 10L);
    
    struct curl_slist* headers = nullptr;
    headers = curl_slist_append(headers, "Content-Type: application/json");
    
    if (!m_authToken.empty()) {
        std::string authHeader = "Authorization: Bearer " + m_authToken;
        headers = curl_slist_append(headers, authHeader.c_str());
    }
    
    curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
    
    if (method == "POST") {
        curl_easy_setopt(curl, CURLOPT_POST, 1L);
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data.c_str());
    } else if (method == "PUT") {
        curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data.c_str());
    } else if (method == "DELETE") {
        curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    }
    
    CURLcode res = curl_easy_perform(curl);
    curl_slist_free_all(headers);
    
    if (res != CURLE_OK) {
        std::cout << "CURL Error: " << curl_easy_strerror(res) << std::endl;
        return "";
    }
    
    std::cout << "Response: " << writeData.data << std::endl;
    return writeData.data;
}

ApiStatus ApiClient::parseResponse(const std::string& response, std::string& outMessage) {
    if (response.empty()) {
        outMessage = "Network error";
        return ApiStatus::NetworkError;
    }
    
    try {
        json j = json::parse(response);
        bool success = j.value("success", false);
        outMessage = j.value("message", "Unknown error");
        
        if (!success) {
            if (outMessage.find("authentication") != std::string::npos) {
                return ApiStatus::AuthError;
            }
            if (outMessage.find("validation") != std::string::npos) {
                return ApiStatus::ValidationError;
            }
            if (outMessage.find("not found") != std::string::npos) {
                return ApiStatus::NotFound;
            }
            return ApiStatus::ParseError;
        }
        
        return ApiStatus::Success;
    } catch (const json::exception&) {
        outMessage = "Invalid JSON response";
        return ApiStatus::ParseError;
    }
}

ApiResult<std::string> ApiClient::login(const std::string& mail, const std::string& password) {
    json requestData;
    requestData["mail"] = mail;
    requestData["password"] = password;
    
    std::string response = makeRequest("/api/auth/login", "POST", requestData.dump());
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<std::string> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            if (j.contains("data") && j["data"].contains("token")) {
                result.data = j["data"]["token"];
            }
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse login response";
        }
    }
    
    return result;
}

ApiResponse ApiClient::registerUser(const std::string& mail, const std::string& password,
                                   const std::string& firstname, const std::string& lastname) {
    json requestData;
    requestData["mail"] = mail;
    requestData["password"] = password;
    requestData["firstname"] = firstname;
    requestData["lastname"] = lastname;
    
    std::string response = makeRequest("/api/auth/register", "POST", requestData.dump());
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    return {status, message};
}

ApiResult<std::vector<Event>> ApiClient::getEvents() {
    std::string response = makeRequest("/api/events");
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<std::vector<Event>> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            std::vector<Event> events;
            
            if (j.contains("data") && j["data"].is_array()) {
                for (const auto& eventJson : j["data"]) {
                    Event event;
                    event.setId(eventJson.value("id", 0));
                    event.setName(eventJson.value("name", ""));
                    event.setDescription(eventJson.value("description", ""));
                    event.setStartDate(eventJson.value("startDate", ""));
                    event.setEndDate(eventJson.value("endDate", ""));
                    event.setOwnerId(eventJson.value("ownerId", 0));
                    events.push_back(event);
                }
            }
            
            result.data = events;
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse events response";
        }
    }
    
    return result;
}

ApiResult<Event> ApiClient::getEvent(int id) {
    std::string response = makeRequest("/api/events/" + std::to_string(id));
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<Event> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            if (j.contains("data") && !j["data"].is_null()) {
                Event event;
                const auto& eventJson = j["data"];
                event.setId(eventJson.value("id", 0));
                event.setName(eventJson.value("name", ""));
                event.setDescription(eventJson.value("description", ""));
                event.setStartDate(eventJson.value("startDate", ""));
                event.setEndDate(eventJson.value("endDate", ""));
                event.setOwnerId(eventJson.value("ownerId", 0));
                result.data = event;
            }
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse event response";
        }
    }
    
    return result;
}

ApiResult<int> ApiClient::createEvent(const Event& event) {
    json requestData;
    requestData["name"] = event.getName();
    requestData["description"] = event.getDescription();
    requestData["startDate"] = event.getStartDate();
    requestData["endDate"] = event.getEndDate();
    requestData["ownerId"] = event.getOwnerId();
    
    std::string response = makeRequest("/api/events", "POST", requestData.dump());
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<int> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            if (j.contains("data") && j["data"].contains("id")) {
                result.data = j["data"]["id"];
            }
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse create event response";
        }
    }
    
    return result;
}

ApiResponse ApiClient::updateEvent(int id, const Event& event) {
    json requestData;
    requestData["name"] = event.getName();
    requestData["description"] = event.getDescription();
    requestData["startDate"] = event.getStartDate();
    requestData["endDate"] = event.getEndDate();
    requestData["ownerId"] = event.getOwnerId();
    
    std::string response = makeRequest("/api/events/" + std::to_string(id), "PUT", requestData.dump());
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    return {status, message};
}

ApiResponse ApiClient::deleteEvent(int id) {
    std::string response = makeRequest("/api/events/" + std::to_string(id), "DELETE");
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    return {status, message};
}

ApiResult<std::vector<User>> ApiClient::getUsers() {
    std::string response = makeRequest("/api/users");
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<std::vector<User>> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            std::vector<User> users;
            
            if (j.contains("data") && j["data"].is_array()) {
                for (const auto& userJson : j["data"]) {
                    User user;
                    user.setId(userJson.value("id", 0));
                    user.setMail(userJson.value("mail", ""));
                    user.setIdRole(userJson.value("idRole", 0));
                    user.setIdProfile(userJson.value("idProfile", 0));
                    user.setIsActive(userJson.value("isActive", 1) == 1);
                    user.setCreationDate(userJson.value("creationDate", ""));
                    user.setUpdateDate(userJson.value("updateDate", ""));
                    users.push_back(user);
                }
            }
            
            result.data = users;
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse users response";
        }
    }
    
    return result;
}

ApiResult<User> ApiClient::getUser(int id) {
    std::string response = makeRequest("/api/users/" + std::to_string(id));
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<User> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            if (j.contains("data") && !j["data"].is_null()) {
                User user;
                const auto& userJson = j["data"];
                user.setId(userJson.value("id", 0));
                user.setMail(userJson.value("mail", ""));
                user.setIdRole(userJson.value("idRole", 0));
                user.setIdProfile(userJson.value("idProfile", 0));
                user.setIsActive(userJson.value("isActive", 1) == 1);
                user.setCreationDate(userJson.value("creationDate", ""));
                user.setUpdateDate(userJson.value("updateDate", ""));
                result.data = user;
            }
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse user response";
        }
    }
    
    return result;
}

ApiResult<std::vector<Event>> ApiClient::getUserEvents(int userId) {
    std::string response = makeRequest("/api/users/" + std::to_string(userId) + "/events");
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    ApiResult<std::vector<Event>> result{status, message, std::nullopt};
    
    if (status == ApiStatus::Success) {
        try {
            json j = json::parse(response);
            std::vector<Event> events;
            
            if (j.contains("data") && j["data"].is_array()) {
                for (const auto& eventJson : j["data"]) {
                    Event event;
                    event.setId(eventJson.value("id", 0));
                    event.setName(eventJson.value("name", ""));
                    event.setDescription(eventJson.value("description", ""));
                    event.setStartDate(eventJson.value("startDate", ""));
                    event.setEndDate(eventJson.value("endDate", ""));
                    event.setOwnerId(eventJson.value("ownerId", 0));
                    events.push_back(event);
                }
            }
            
            result.data = events;
        } catch (const json::exception&) {
            result.status = ApiStatus::ParseError;
            result.message = "Failed to parse user events response";
        }
    }
    
    return result;
}

ApiResponse ApiClient::registerForEvent(int eventId, int userId) {
    json requestData;
    requestData["userId"] = userId;
    
    std::string response = makeRequest("/api/events/" + std::to_string(eventId) + "/register", "POST", requestData.dump());
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    return {status, message};
}

ApiResponse ApiClient::unregisterFromEvent(int eventId, int userId) {
    json requestData;
    requestData["userId"] = userId;
    
    std::string response = makeRequest("/api/events/" + std::to_string(eventId) + "/unregister", "POST", requestData.dump());
    std::string message;
    ApiStatus status = parseResponse(response, message);
    
    return {status, message};
}
