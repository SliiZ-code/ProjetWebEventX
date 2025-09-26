#include "UserController.hpp"

UserController::UserController(ApiClient& apiClient, UsersView& view)
    : m_apiClient(apiClient), m_view(view) {}

void UserController::initialize() {
    m_view.setOnAddUser([this]() { handleAddUser(); });
    m_view.setOnEditUser([this](int id) { handleEditUser(id); });
    m_view.setOnDeleteUser([this](int id) { handleDeleteUser(id); });
    m_view.setOnRefresh([this]() { loadUsers(); });
    
    loadUsers();
}

void UserController::loadUsers() {
    auto result = m_apiClient.getUsers();
    
    if (result.status == ApiStatus::Success && result.data.has_value()) {
        m_users = *result.data;
        m_view.setUsers(m_users);
        m_view.showMessage("Users loaded successfully");
    } else {
        m_view.showMessage("Failed to load users: " + result.message);
    }
}

void UserController::handleAddUser() {
    auto result = m_apiClient.registerUser("new@example.com", "password123", "New", "User");
    
    if (result.status == ApiStatus::Success) {
        m_view.showMessage("User created successfully");
        loadUsers();
    } else {
        m_view.showMessage("Failed to create user: " + result.message);
    }
}

void UserController::handleEditUser(int userId) {
    m_view.showMessage("Edit user functionality not implemented yet");
}

void UserController::handleDeleteUser(int userId) {
    m_view.showMessage("Delete user functionality not available in API");
}
