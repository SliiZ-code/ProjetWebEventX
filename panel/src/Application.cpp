#include "Application.hpp"
#include "models/ApiClient.hpp"
#include "views/EventsView.hpp"
#include "views/UsersView.hpp"
#include "controllers/EventController.hpp"
#include "controllers/UserController.hpp"

const std::string Application::API_BASE_URL = "http://localhost:8000";

Application::Application() 
    : m_window(sf::VideoMode({800, 600}), "EventX Admin Panel")
    , m_currentView(nullptr)
    , m_currentViewType(ViewType::Events) {
    
    m_window.setFramerateLimit(60);
    
    try {
        m_apiClient = std::make_unique<ApiClient>(API_BASE_URL);
        initializeViews();
        initializeControllers();
        switchToView(ViewType::Events);
    } catch (const std::exception& e) {
        throw;
    }
}

Application::~Application() = default;

int Application::run() {
    while (m_window.isOpen()) {
        float deltaTime = m_clock.restart().asSeconds();
        
        handleEvents();
        update(deltaTime);
        render();
    }
    
    return 0;
}

void Application::handleEvents() {
    while (const std::optional event = m_window.pollEvent()) {
        if (event->is<sf::Event::Closed>()) {
            m_window.close();
        }
        
        if (m_currentView) {
            m_currentView->handleEvent(*event);
        }
    }
}

void Application::update(float deltaTime) {
    if (m_currentView) {
        m_currentView->update(deltaTime);
    }
}

void Application::render() {
    m_window.clear(sf::Color(240, 240, 240));
    
    if (m_currentView) {
        m_currentView->render(m_window);
    }
    
    m_window.display();
}

void Application::switchToView(ViewType viewType) {
    m_currentViewType = viewType;
    
    switch (viewType) {
        case ViewType::Events:
            m_currentView = m_eventsView.get();
            break;
        case ViewType::Users:
            m_currentView = m_usersView.get();
            break;
    }
    
    if (m_currentView) {
        m_currentView->setVisible(true);
    }
}

void Application::initializeViews() {
    m_eventsView = std::make_unique<EventsView>();
    m_usersView = std::make_unique<UsersView>();
    
    m_eventsView->setOnSwitchToUsers([this]() {
        switchToView(ViewType::Users);
    });
    
    m_usersView->setOnSwitchToEvents([this]() {
        switchToView(ViewType::Events);
    });
}

void Application::initializeControllers() {
    m_eventController = std::make_unique<EventController>(*m_apiClient, *m_eventsView);
    m_userController = std::make_unique<UserController>(*m_apiClient, *m_usersView);
    
    m_eventController->initialize();
    m_userController->initialize();
}
