#include "Button.hpp"

Button::Button() : Button("Button") {}

Button::Button(const std::string& text) 
    : m_position(0, 0)
    , m_size(100, 30)
    , m_text(text)
    , m_normalColor(sf::Color(100, 100, 100))
    , m_hoverColor(sf::Color(150, 150, 150))
    , m_pressedColor(sf::Color(80, 80, 80))
    , m_currentColor(m_normalColor) {
    // Le texte sera créé dans setFont()
}

void Button::setPosition(sf::Vector2f position) {
    m_position = position;
    updateTextPosition();
}

void Button::setSize(sf::Vector2f size) {
    m_size = size;
    updateTextPosition();
}

void Button::setText(const std::string& text) {
    m_text = text;
    if (m_textObj) {
        m_textObj->setString(m_text);
    }
    updateTextPosition();
}

void Button::setFont(const sf::Font& font) {
    m_font = font;
    // Créer le texte avec la police
    m_textObj = sf::Text(m_font, m_text, 14);  // Taille de police plus petite
    m_textObj->setFillColor(sf::Color::White);
    updateTextPosition();
}

void Button::setColors(sf::Color normal, sf::Color hover, sf::Color pressed) {
    m_normalColor = normal;
    m_hoverColor = hover;
    m_pressedColor = pressed;
    m_currentColor = m_normalColor;
}

void Button::setOnClick(std::function<void()> callback) {
    m_onClick = callback;
}

void Button::handleEvent(const sf::Event& event) {
    if (const auto* mousePressed = event.getIf<sf::Event::MouseButtonPressed>()) {
        if (mousePressed->button == sf::Mouse::Button::Left) {
            sf::Vector2f mousePosF(static_cast<float>(mousePressed->position.x), static_cast<float>(mousePressed->position.y));
            if (contains(mousePosF)) {
                m_isPressed = true;
                m_currentColor = m_pressedColor;
            }
        }
    } else if (const auto* mouseReleased = event.getIf<sf::Event::MouseButtonReleased>()) {
        if (mouseReleased->button == sf::Mouse::Button::Left && m_isPressed) {
            sf::Vector2f mousePosF(static_cast<float>(mouseReleased->position.x), static_cast<float>(mouseReleased->position.y));
            if (contains(mousePosF) && m_onClick) {
                m_onClick();
            }
            m_isPressed = false;
        }
    }
}

void Button::update(sf::Vector2f mousePos) {
    m_isHovered = contains(mousePos);
    
    if (!m_isPressed) {
        if (m_isHovered) {
            m_currentColor = m_hoverColor;
        } else {
            m_currentColor = m_normalColor;
        }
    }
}

bool Button::contains(sf::Vector2f point) const {
    return point.x >= m_position.x && point.x <= m_position.x + m_size.x &&
           point.y >= m_position.y && point.y <= m_position.y + m_size.y;
}

void Button::updateTextPosition() {
    if (m_textObj) {
        sf::FloatRect textBounds = m_textObj->getLocalBounds();
        sf::Vector2f textPosition;
        textPosition.x = m_position.x + (m_size.x - textBounds.size.x) / 2 - textBounds.position.x;
        textPosition.y = m_position.y + (m_size.y - textBounds.size.y) / 2 - textBounds.position.y;
        m_textObj->setPosition(textPosition);
    }
}

void Button::draw(sf::RenderTarget& target, sf::RenderStates states) const {
    m_background.setPosition(m_position);
    m_background.setSize(m_size);
    m_background.setFillColor(m_currentColor);
    m_background.setOutlineColor(sf::Color::Black);
    m_background.setOutlineThickness(1.0f);
    
    target.draw(m_background);
    if (m_textObj) {
        target.draw(*m_textObj);
    }
}
