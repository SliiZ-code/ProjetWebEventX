#pragma once
#include "BaseView.hpp"
#include "../models/User.hpp"
#include "components/Table.hpp"
#include "components/Button.hpp"
#include <vector>
#include <functional>
#include <optional>

class UsersView : public BaseView {
public:
    UsersView();
    
    void handleEvent(const sf::Event& event) override;
    void update(float deltaTime) override;
    void render(sf::RenderTarget& target) override;
    
    void setUsers(const std::vector<User>& users);
    void setOnAddUser(std::function<void()> callback);
    void setOnEditUser(std::function<void(int)> callback);
    void setOnDeleteUser(std::function<void(int)> callback);
    void setOnRefresh(std::function<void()> callback);
    void setOnSwitchToEvents(std::function<void()> callback);
    
    void showMessage(const std::string& message);

private:
    Table m_table;
    Button m_addButton;
    Button m_refreshButton;
    Button m_eventsButton;
    
    std::optional<sf::Text> m_titleText;
    std::optional<sf::Text> m_messageText;
    
    std::string m_currentMessage;
    float m_messageTimer = 0.0f;
    
    void setupLayout();
};
