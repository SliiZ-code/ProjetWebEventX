#pragma once
#include <SFML/Graphics.hpp>
#include <string>
#include <functional>
#include <optional>

class Button : public sf::Drawable {
public:
    Button();
    Button(const std::string& text);
    
    void setPosition(sf::Vector2f position);
    void setSize(sf::Vector2f size);
    void setText(const std::string& text);
    void setFont(const sf::Font& font);
    void setColors(sf::Color normal, sf::Color hover, sf::Color pressed);
    
    void setOnClick(std::function<void()> callback);
    
    void handleEvent(const sf::Event& event);
    void update(sf::Vector2f mousePos);
    
    bool contains(sf::Vector2f point) const;

private:
    void draw(sf::RenderTarget& target, sf::RenderStates states) const override;
    void updateTextPosition();
    
    sf::Vector2f m_position;
    sf::Vector2f m_size;
    std::string m_text;
    sf::Font m_font;
    
    sf::Color m_normalColor;
    sf::Color m_hoverColor;
    sf::Color m_pressedColor;
    sf::Color m_currentColor;
    
    std::function<void()> m_onClick;
    
    bool m_isHovered = false;
    bool m_isPressed = false;
    
    mutable sf::RectangleShape m_background;
    mutable std::optional<sf::Text> m_textObj;
};
