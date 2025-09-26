#include "UsersView.hpp"

UsersView::UsersView() 
    : m_addButton("Add User")
    , m_refreshButton("Refresh")
    , m_eventsButton("Events") {
    
    loadResources();
    setupLayout();
}

void UsersView::setupLayout() {
    // Créer les textes avec la police chargée
    m_titleText = sf::Text(m_font, "Users Management", 18);
    m_titleText->setFillColor(sf::Color::Black);
    m_titleText->setPosition(sf::Vector2f(20, 10));
    
    m_messageText = sf::Text(m_font, "", 12);
    m_messageText->setFillColor(sf::Color::Red);
    m_messageText->setPosition(sf::Vector2f(20, 35));
    
    m_eventsButton.setPosition(sf::Vector2f(20, 55));
    m_eventsButton.setSize(sf::Vector2f(80, 25));
    m_eventsButton.setFont(m_font);
    
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
    m_table.setHeaders({"ID", "Email", "Role", "Profile"});
}

void UsersView::handleEvent(const sf::Event& event) {
    m_addButton.handleEvent(event);
    m_refreshButton.handleEvent(event);
    m_eventsButton.handleEvent(event);
    m_table.handleEvent(event);
}

void UsersView::update(float deltaTime) {
    if (m_messageTimer > 0.0f) {
        m_messageTimer -= deltaTime;
        if (m_messageTimer <= 0.0f) {
            m_currentMessage.clear();
        }
    }
}

void UsersView::render(sf::RenderTarget& target) {
    if (m_titleText) {
        target.draw(*m_titleText);
    }
    
    if (!m_currentMessage.empty() && m_messageText) {
        m_messageText->setString(m_currentMessage);
        target.draw(*m_messageText);
    }
    
    target.draw(m_eventsButton);
    target.draw(m_addButton);
    target.draw(m_refreshButton);
    target.draw(m_table);
}

void UsersView::setUsers(const std::vector<User>& users) {
    std::vector<TableRow> rows;
    
    for (const User& user : users) {
        TableRow row;
        row.id = user.getId();
        row.data = {
            std::to_string(user.getId()),
            user.getMail(),
            "Role " + std::to_string(user.getIdRole()),
            "Profile " + std::to_string(user.getIdProfile())
        };
        rows.push_back(row);
    }
    
    m_table.setRows(rows);
}

void UsersView::setOnAddUser(std::function<void()> callback) {
    m_addButton.setOnClick(callback);
}

void UsersView::setOnEditUser(std::function<void(int)> callback) {
    m_table.setOnEdit(callback);
}

void UsersView::setOnDeleteUser(std::function<void(int)> callback) {
    m_table.setOnDelete(callback);
}

void UsersView::setOnRefresh(std::function<void()> callback) {
    m_refreshButton.setOnClick(callback);
}

void UsersView::setOnSwitchToEvents(std::function<void()> callback) {
    m_eventsButton.setOnClick(callback);
}

void UsersView::showMessage(const std::string& message) {
    m_currentMessage = message;
    m_messageTimer = 3.0f;
}
