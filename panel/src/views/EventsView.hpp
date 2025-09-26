#pragma once
#include "BaseView.hpp"
#include "../models/Event.hpp"
#include "components/Table.hpp"
#include "components/Button.hpp"
#include <vector>
#include <functional>
#include <optional>

class EventsView : public BaseView {
public:
    EventsView();
    
    void handleEvent(const sf::Event& event) override;
    void update(float deltaTime) override;
    void render(sf::RenderTarget& target) override;
    
    void setEvents(const std::vector<Event>& events);
    void setOnAddEvent(std::function<void()> callback);
    void setOnEditEvent(std::function<void(int)> callback);
    void setOnDeleteEvent(std::function<void(int)> callback);
    void setOnRefresh(std::function<void()> callback);
    void setOnSwitchToUsers(std::function<void()> callback);
    
    void showMessage(const std::string& message);

private:
    Table m_table;
    Button m_addButton;
    Button m_refreshButton;
    Button m_usersButton;
    
    std::optional<sf::Text> m_titleText;
    std::optional<sf::Text> m_messageText;
    
    std::string m_currentMessage;
    float m_messageTimer = 0.0f;
    
    void setupLayout();
};
