#include "Table.hpp"
#include <algorithm>

Table::Table() : m_position(0, 0), m_size(800, 400) {}

void Table::setPosition(sf::Vector2f position) {
    m_position = position;
    updateLayout();
}

void Table::setSize(sf::Vector2f size) {
    m_size = size;
    updateLayout();
}

void Table::setHeaders(const std::vector<std::string>& headers) {
    m_headers = headers;
    updateLayout();
}

void Table::setRows(const std::vector<TableRow>& rows) {
    m_rows = rows;
}

void Table::setFont(const sf::Font& font) {
    m_font = font;
    // Créer le texte avec une taille plus petite pour les tableaux
    m_text = sf::Text(m_font, "", 12);
    m_text->setFillColor(sf::Color::Black);
}

void Table::setOnEdit(std::function<void(int)> callback) {
    m_onEdit = callback;
}

void Table::setOnDelete(std::function<void(int)> callback) {
    m_onDelete = callback;
}

void Table::updateLayout() {
    if (m_headers.empty()) return;
    
    float actionColumnWidth = 120.0f;
    float availableWidth = m_size.x - actionColumnWidth;
    float columnWidth = availableWidth / m_headers.size();
    
    m_columnWidths.clear();
    for (size_t i = 0; i < m_headers.size(); ++i) {
        m_columnWidths.push_back(columnWidth);
    }
    m_columnWidths.push_back(actionColumnWidth);
}

void Table::handleClick(sf::Vector2f mousePos) {
    for (size_t i = 0; i < m_rows.size(); ++i) {
        sf::FloatRect editBounds = getEditButtonBounds(i);
        sf::FloatRect deleteBounds = getDeleteButtonBounds(i);
        
        if (editBounds.contains(mousePos) && m_onEdit) {
            m_onEdit(m_rows[i].id);
            return;
        }
        
        if (deleteBounds.contains(mousePos) && m_onDelete) {
            m_onDelete(m_rows[i].id);
            return;
        }
    }
}

void Table::handleEvent(const sf::Event& event) {
    if (const auto* mousePressed = event.getIf<sf::Event::MouseButtonPressed>()) {
        if (mousePressed->button == sf::Mouse::Button::Left) {
            sf::Vector2f mousePosF(static_cast<float>(mousePressed->position.x), static_cast<float>(mousePressed->position.y));
            handleClick(mousePosF);
        }
    }
}

sf::FloatRect Table::getRowBounds(size_t rowIndex) const {
    float y = m_position.y + m_headerHeight + rowIndex * m_rowHeight;
    return sf::FloatRect(sf::Vector2f(m_position.x, y), sf::Vector2f(m_size.x, m_rowHeight));
}

sf::FloatRect Table::getEditButtonBounds(size_t rowIndex) const {
    sf::FloatRect rowBounds = getRowBounds(rowIndex);
    float actionColumnStart = m_position.x + m_size.x - 120.0f;
    return sf::FloatRect(sf::Vector2f(actionColumnStart + 5, rowBounds.position.y + 5), sf::Vector2f(50, 20));
}

sf::FloatRect Table::getDeleteButtonBounds(size_t rowIndex) const {
    sf::FloatRect rowBounds = getRowBounds(rowIndex);
    float actionColumnStart = m_position.x + m_size.x - 120.0f;
    return sf::FloatRect(sf::Vector2f(actionColumnStart + 60, rowBounds.position.y + 5), sf::Vector2f(50, 20));
}

void Table::draw(sf::RenderTarget& target, sf::RenderStates states) const {
    if (m_headers.empty()) return;
    
    m_background.setFillColor(sf::Color::White);
    m_background.setOutlineColor(sf::Color::Black);
    m_background.setOutlineThickness(1.0f);
    
    if (!m_text) return;  // Pas de texte si police pas chargée
    
    m_background.setPosition(m_position);
    m_background.setSize(sf::Vector2f(m_size.x, m_headerHeight));
    m_background.setFillColor(sf::Color(220, 220, 220));
    target.draw(m_background);
    
    float currentX = m_position.x;
    for (size_t i = 0; i < m_headers.size(); ++i) {
        m_text->setString(m_headers[i]);
        m_text->setPosition(sf::Vector2f(currentX + 5, m_position.y + 5));
        target.draw(*m_text);
        currentX += m_columnWidths[i];
    }
    
    m_text->setString("Actions");
    m_text->setPosition(sf::Vector2f(currentX + 5, m_position.y + 5));
    target.draw(*m_text);
    
    for (size_t rowIndex = 0; rowIndex < m_rows.size(); ++rowIndex) {
        float rowY = m_position.y + m_headerHeight + rowIndex * m_rowHeight;
        
        m_background.setPosition(sf::Vector2f(m_position.x, rowY));
        m_background.setSize(sf::Vector2f(m_size.x, m_rowHeight));
        m_background.setFillColor(rowIndex % 2 == 0 ? sf::Color::White : sf::Color(245, 245, 245));
        target.draw(m_background);
        
        currentX = m_position.x;
        const TableRow& row = m_rows[rowIndex];
        
        for (size_t colIndex = 0; colIndex < std::min(row.data.size(), m_columnWidths.size() - 1); ++colIndex) {
            m_text->setString(row.data[colIndex]);
            m_text->setPosition(sf::Vector2f(currentX + 5, rowY + 5));
            target.draw(*m_text);
            currentX += m_columnWidths[colIndex];
        }
        
        sf::FloatRect editBounds = getEditButtonBounds(rowIndex);
        sf::FloatRect deleteBounds = getDeleteButtonBounds(rowIndex);
        
        m_background.setPosition(sf::Vector2f(editBounds.position.x, editBounds.position.y));
        m_background.setSize(sf::Vector2f(editBounds.size.x, editBounds.size.y));
        m_background.setFillColor(sf::Color(100, 150, 255));
        target.draw(m_background);
        
        m_text->setString("Edit");
        m_text->setPosition(sf::Vector2f(editBounds.position.x + 15, editBounds.position.y + 2));
        target.draw(*m_text);
        
        m_background.setPosition(sf::Vector2f(deleteBounds.position.x, deleteBounds.position.y));
        m_background.setSize(sf::Vector2f(deleteBounds.size.x, deleteBounds.size.y));
        m_background.setFillColor(sf::Color(255, 100, 100));
        target.draw(m_background);
        
        m_text->setString("Delete");
        m_text->setFillColor(sf::Color::White);
        m_text->setPosition(sf::Vector2f(deleteBounds.position.x + 10, deleteBounds.position.y + 2));
        target.draw(*m_text);
        
        m_text->setFillColor(sf::Color::Black);
    }
}
