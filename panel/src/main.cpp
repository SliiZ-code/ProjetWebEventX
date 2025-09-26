#include "Application.hpp"
#include <iostream>
#include <exception>

int main() {
    try {
        Application app;
        return app.run();
    } catch (const std::exception& e) {
        std::cerr << "Application error: " << e.what() << std::endl;
        return 1;
    } catch (...) {
        std::cerr << "Unknown error occurred" << std::endl;
        return 1;
    }
}