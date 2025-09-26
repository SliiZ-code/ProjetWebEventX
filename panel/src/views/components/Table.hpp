#pragma once
#include <SFML/Graphics.hpp>
#include <vector>
#include <string>
#include <functional>
#include <optional>

struct TableRow {
    std::vector<std::string> data;
    int id;
};

class Table : public sf::Drawable {
public:
    Table();
    
    void setPosition(sf::Vector2f position);
    void setSize(sf::Vector2f size);
    void setHeaders(const std::vector<std::string>& headers);
    void setRows(const std::vector<TableRow>& rows);
    void setFont(const sf::Font& font);
    
    void setOnEdit(std::function<void(int)> callback);
    void setOnDelete(std::function<void(int)> callback);
    
    void handleClick(sf::Vector2f mousePos);
    void handleEvent(const sf::Event& event);

private:
    void draw(sf::RenderTarget& target, sf::RenderStates states) const override;
    void updateLayout();
    sf::FloatRect getRowBounds(size_t rowIndex) const;
    sf::FloatRect getEditButtonBounds(size_t rowIndex) const;
    sf::FloatRect getDeleteButtonBounds(size_t rowIndex) const;
    
    sf::Vector2f m_position;
    sf::Vector2f m_size;
    std::vector<std::string> m_headers;
    std::vector<TableRow> m_rows;
    sf::Font m_font;
    
    std::function<void(int)> m_onEdit;
    std::function<void(int)> m_onDelete;
    
    float m_rowHeight = 22.0f;
    float m_headerHeight = 25.0f;
    std::vector<float> m_columnWidths;
    
    mutable sf::RectangleShape m_background;
    mutable std::optional<sf::Text> m_text;
};
