#include "EventsView.hpp"

EventsView::EventsView() 
    : m_addButton("Add Event")
    , m_refreshButton("Refresh")
    , m_usersButton("Users") {
    
    loadResources();
    setupLayout();
}

void EventsView::setupLayout() {
    // Créer les textes avec la police chargée
    m_titleText = sf::Text(m_font, "Events Management", 18);
    m_titleText->setFillColor(sf::Color::Black);
    m_titleText->setPosition(sf::Vector2f(20, 10));
    
    m_messageText = sf::Text(m_font, "", 12);
    m_messageText->setFillColor(sf::Color::Red);
    m_messageText->setPosition(sf::Vector2f(20, 35));
    
    m_usersButton.setPosition(sf::Vector2f(20, 55));
    m_usersButton.setSize(sf::Vector2f(80, 25));
    m_usersButton.setFont(m_font);
    
    m_addButton.setPosition(sf::Vector2f(110, 55));
    m_addButton.setSize(sf::Vector2f(80, 25));
    m_addButton.setFont(m_font);
    m_addButton.setColors(sf::Color(0, 150, 0), sf::Color(0, 180, 0), sf::Color(0, 120, 0));
    
    m_refreshButton.setPosition(sf::Vector2f(200, 55));
    m_refreshButton.setSize(sf::Vector2f(80, 25));
    m_refreshButton.setFont(m_font);
    
    m_table.setPosition(sf::Vector2f(20, 90));
    m_table.setSize(sf::Vector2f(760, 400));
    m_table.setFont(m_font);
    m_table.setHeaders({"ID", "Name", "Description", "Start Date", "End Date", "Owner ID"});
}

void EventsView::handleEvent(const sf::Event& event) {
    m_addButton.handleEvent(event);
    m_refreshButton.handleEvent(event);
    m_usersButton.handleEvent(event);
    m_table.handleEvent(event);
}

void EventsView::update(float deltaTime) {
    if (m_messageTimer > 0.0f) {
        m_messageTimer -= deltaTime;
        if (m_messageTimer <= 0.0f) {
            m_currentMessage.clear();
        }
    }
}

void EventsView::render(sf::RenderTarget& target) {
    if (m_titleText) {
        target.draw(*m_titleText);
    }
    
    if (!m_currentMessage.empty() && m_messageText) {
        m_messageText->setString(m_currentMessage);
        target.draw(*m_messageText);
    }
    
    target.draw(m_usersButton);
    target.draw(m_addButton);
    target.draw(m_refreshButton);
    target.draw(m_table);
}

void EventsView::setEvents(const std::vector<Event>& events) {
    std::vector<TableRow> rows;
    
    for (const Event& event : events) {
        TableRow row;
        row.id = event.getId();
        row.data = {
            std::to_string(event.getId()),
            event.getName(),
            event.getDescription(),
            event.getStartDate(),
            event.getEndDate(),
            std::to_string(event.getOwnerId())
        };
        rows.push_back(row);
    }
    
    m_table.setRows(rows);
}

void EventsView::setOnAddEvent(std::function<void()> callback) {
    m_addButton.setOnClick(callback);
}

void EventsView::setOnEditEvent(std::function<void(int)> callback) {
    m_table.setOnEdit(callback);
}

void EventsView::setOnDeleteEvent(std::function<void(int)> callback) {
    m_table.setOnDelete(callback);
}

void EventsView::setOnRefresh(std::function<void()> callback) {
    m_refreshButton.setOnClick(callback);
}

void EventsView::setOnSwitchToUsers(std::function<void()> callback) {
    m_usersButton.setOnClick(callback);
}

void EventsView::showMessage(const std::string& message) {
    m_currentMessage = message;
    m_messageTimer = 3.0f;
}
