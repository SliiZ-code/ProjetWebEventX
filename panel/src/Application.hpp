#pragma once
#include <SFML/Graphics.hpp>
#include <memory>

class ApiClient;
class BaseView;
class EventsView;
class UsersView;
class EventController;
class UserController;

enum class ViewType {
    Events,
    Users
};

class Application {
public:
    Application();
    ~Application();
    
    int run();

private:
    void handleEvents();
    void update(float deltaTime);
    void render();
    
    void switchToView(ViewType viewType);
    void initializeViews();
    void initializeControllers();
    
    sf::RenderWindow m_window;
    sf::Clock m_clock;
    
    std::unique_ptr<ApiClient> m_apiClient;
    std::unique_ptr<EventsView> m_eventsView;
    std::unique_ptr<UsersView> m_usersView;
    std::unique_ptr<EventController> m_eventController;
    std::unique_ptr<UserController> m_userController;
    
    BaseView* m_currentView;
    ViewType m_currentViewType;
    
    static const std::string API_BASE_URL;
};
