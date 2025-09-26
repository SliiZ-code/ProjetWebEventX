#pragma once
#include "../models/Event.hpp"
#include "../models/ApiClient.hpp"
#include "../views/EventsView.hpp"
#include <memory>

class EventController {
public:
    EventController(ApiClient& apiClient, EventsView& view);
    
    void initialize();
    void loadEvents();
    void handleAddEvent();
    void handleEditEvent(int eventId);
    void handleDeleteEvent(int eventId);

private:
    ApiClient& m_apiClient;
    EventsView& m_view;
    std::vector<Event> m_events;
};
