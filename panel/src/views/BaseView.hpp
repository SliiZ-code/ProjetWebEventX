#pragma once
#include <SFML/Graphics.hpp>

class BaseView {
public:
    BaseView() = default;
    virtual ~BaseView() = default;
    
    virtual void handleEvent(const sf::Event& event) = 0;
    virtual void update(float deltaTime) = 0;
    virtual void render(sf::RenderTarget& target) = 0;
    
    void setVisible(bool visible) { m_visible = visible; }
    bool isVisible() const { return m_visible; }

protected:
    bool m_visible = true;
    sf::Font m_font;
    
    virtual void loadResources();
};
