#include "User.hpp"

User::User(int id, const std::string& mail, int idRole, int idProfile)
    : m_id(id), m_mail(mail), m_idRole(idRole), m_idProfile(idProfile) {
}
