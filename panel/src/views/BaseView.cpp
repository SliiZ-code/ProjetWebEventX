#include "BaseView.hpp"
#include <iostream>

void BaseView::loadResources() {
    // Essayons plusieurs chemins de police systeme
    bool fontLoaded = false;
    
    // macOS system fonts
    std::vector<std::string> fontPaths = {
        "/System/Library/Fonts/Arial.ttf",
        "/System/Library/Fonts/Helvetica.ttc", 
        "/Library/Fonts/Arial.ttf",
        "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf", // Linux fallback
        "arial.ttf" // Windows fallback
    };
    
    for (const std::string& path : fontPaths) {
        if (m_font.openFromFile(path)) {
            std::cout << "Font loaded successfully from: " << path << std::endl;
            fontLoaded = true;
            break;
        }
    }
    
    if (!fontLoaded) {
        std::cout << "Warning: No font could be loaded, using default font" << std::endl;
        // SFML utilisera la police par défaut
    }
}
