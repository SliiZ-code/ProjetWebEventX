#pragma once
#include <string>

class User {
public:
    User() = default;
    User(int id, const std::string& mail, int idRole, int idProfile);
    
    int getId() const { return m_id; }
    const std::string& getMail() const { return m_mail; }
    int getIdRole() const { return m_idRole; }
    int getIdProfile() const { return m_idProfile; }
    bool getIsActive() const { return m_isActive; }
    const std::string& getCreationDate() const { return m_creationDate; }
    const std::string& getUpdateDate() const { return m_updateDate; }
    
    // Compatibilité avec l'ancien code (retourne des valeurs basées sur l'email)
    std::string getFirstname() const { 
        size_t at_pos = m_mail.find('@');
        if (at_pos != std::string::npos) {
            std::string name = m_mail.substr(0, at_pos);
            size_t dot_pos = name.find('.');
            if (dot_pos != std::string::npos) {
                return name.substr(0, dot_pos);
            }
        }
        return "User" + std::to_string(m_id);
    }
    
    std::string getLastname() const { 
        size_t at_pos = m_mail.find('@');
        if (at_pos != std::string::npos) {
            std::string name = m_mail.substr(0, at_pos);
            size_t dot_pos = name.find('.');
            if (dot_pos != std::string::npos && dot_pos + 1 < name.length()) {
                return name.substr(dot_pos + 1);
            }
        }
        return "Profile" + std::to_string(m_idProfile);
    }
    
    void setId(int id) { m_id = id; }
    void setMail(const std::string& mail) { m_mail = mail; }
    void setIdRole(int idRole) { m_idRole = idRole; }
    void setIdProfile(int idProfile) { m_idProfile = idProfile; }
    void setIsActive(bool isActive) { m_isActive = isActive; }
    void setCreationDate(const std::string& creationDate) { m_creationDate = creationDate; }
    void setUpdateDate(const std::string& updateDate) { m_updateDate = updateDate; }

private:
    int m_id = 0;
    std::string m_mail;
    int m_idRole = 0;
    int m_idProfile = 0;
    bool m_isActive = true;
    std::string m_creationDate;
    std::string m_updateDate;
};
