#include "EventController.hpp"

EventController::EventController(ApiClient& apiClient, EventsView& view)
    : m_apiClient(apiClient), m_view(view) {}

void EventController::initialize() {
    m_view.setOnAddEvent([this]() { handleAddEvent(); });
    m_view.setOnEditEvent([this](int id) { handleEditEvent(id); });
    m_view.setOnDeleteEvent([this](int id) { handleDeleteEvent(id); });
    m_view.setOnRefresh([this]() { loadEvents(); });
    
    loadEvents();
}

void EventController::loadEvents() {
    auto result = m_apiClient.getEvents();
    
    if (result.status == ApiStatus::Success && result.data.has_value()) {
        m_events = *result.data;
        m_view.setEvents(m_events);
        m_view.showMessage("Events loaded successfully");
    } else {
        m_view.showMessage("Failed to load events: " + result.message);
    }
}

void EventController::handleAddEvent() {
    Event newEvent;
    newEvent.setName("New Event");
    newEvent.setDescription("Event description");
    newEvent.setStartDate("2024-01-01 10:00:00");
    newEvent.setOwnerId(1);
    
    auto result = m_apiClient.createEvent(newEvent);
    
    if (result.status == ApiStatus::Success) {
        m_view.showMessage("Event created successfully");
        loadEvents();
    } else {
        m_view.showMessage("Failed to create event: " + result.message);
    }
}

void EventController::handleEditEvent(int eventId) {
    auto eventIt = std::find_if(m_events.begin(), m_events.end(),
        [eventId](const Event& e) { return e.getId() == eventId; });
    
    if (eventIt != m_events.end()) {
        Event updatedEvent = *eventIt;
        updatedEvent.setName(updatedEvent.getName() + " (Updated)");
        
        auto result = m_apiClient.updateEvent(eventId, updatedEvent);
        
        if (result.status == ApiStatus::Success) {
            m_view.showMessage("Event updated successfully");
            loadEvents();
        } else {
            m_view.showMessage("Failed to update event: " + result.message);
        }
    }
}

void EventController::handleDeleteEvent(int eventId) {
    auto result = m_apiClient.deleteEvent(eventId);
    
    if (result.status == ApiStatus::Success) {
        m_view.showMessage("Event deleted successfully");
        loadEvents();
    } else {
        m_view.showMessage("Failed to delete event: " + result.message);
    }
}
