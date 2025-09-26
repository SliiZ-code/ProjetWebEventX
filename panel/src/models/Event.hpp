#pragma once
#include <string>
#include <optional>

class Event {
public:
    Event() = default;
    Event(int id, const std::string& name, const std::string& startDate, int ownerId);
    
    int getId() const { return m_id; }
    const std::string& getName() const { return m_name; }
    const std::string& getDescription() const { return m_description; }
    const std::string& getStartDate() const { return m_startDate; }
    const std::string& getEndDate() const { return m_endDate; }
    int getOwnerId() const { return m_ownerId; }
    
    void setId(int id) { m_id = id; }
    void setName(const std::string& name) { m_name = name; }
    void setDescription(const std::string& description) { m_description = description; }
    void setStartDate(const std::string& startDate) { m_startDate = startDate; }
    void setEndDate(const std::string& endDate) { m_endDate = endDate; }
    void setOwnerId(int ownerId) { m_ownerId = ownerId; }

private:
    int m_id = 0;
    std::string m_name;
    std::string m_description;
    std::string m_startDate;
    std::string m_endDate;
    int m_ownerId = 0;
};
