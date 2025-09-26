#pragma once
#include <string>
#include <vector>
#include <optional>
#include "Event.hpp"
#include "User.hpp"

enum class ApiStatus {
    Success,
    NetworkError,
    ParseError,
    AuthError,
    NotFound,
    ValidationError
};

struct ApiResponse {
    ApiStatus status;
    std::string message;
};

template<typename T>
struct ApiResult {
    ApiStatus status;
    std::string message;
    std::optional<T> data;
};

class ApiClient {
public:
    ApiClient(const std::string& baseUrl);
    ~ApiClient();
    
    void setAuthToken(const std::string& token);
    
    ApiResult<std::string> login(const std::string& mail, const std::string& password);
    ApiResponse registerUser(const std::string& mail, const std::string& password, 
                           const std::string& firstname, const std::string& lastname);
    
    ApiResult<std::vector<Event>> getEvents();
    ApiResult<Event> getEvent(int id);
    ApiResult<int> createEvent(const Event& event);
    ApiResponse updateEvent(int id, const Event& event);
    ApiResponse deleteEvent(int id);
    
    ApiResult<std::vector<User>> getUsers();
    ApiResult<User> getUser(int id);
    ApiResult<std::vector<Event>> getUserEvents(int userId);
    
    ApiResponse registerForEvent(int eventId, int userId);
    ApiResponse unregisterFromEvent(int eventId, int userId);

private:
    std::string makeRequest(const std::string& endpoint, const std::string& method = "GET", 
                           const std::string& data = "");
    ApiStatus parseResponse(const std::string& response, std::string& outMessage);
    
    std::string m_baseUrl;
    std::string m_authToken;
    void* m_curl;
};
