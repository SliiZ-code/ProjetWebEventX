#pragma once
#include "../models/User.hpp"
#include "../models/ApiClient.hpp"
#include "../views/UsersView.hpp"
#include <memory>

class UserController {
public:
    UserController(ApiClient& apiClient, UsersView& view);
    
    void initialize();
    void loadUsers();
    void handleAddUser();
    void handleEditUser(int userId);
    void handleDeleteUser(int userId);

private:
    ApiClient& m_apiClient;
    UsersView& m_view;
    std::vector<User> m_users;
};
