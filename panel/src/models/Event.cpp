#include "Event.hpp"

Event::Event(int id, const std::string& name, const std::string& startDate, int ownerId)
    : m_id(id), m_name(name), m_startDate(startDate), m_ownerId(ownerId) {
}
