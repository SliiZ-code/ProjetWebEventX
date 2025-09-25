cmake_minimum_required(VERSION 3.10)

project(panel)

find_package(SFML COMPONENTS System Window Graphics Network Audio CONFIG REQUIRED)

add_executable(panel src/main.cpp)

target_link_libraries(panel PRIVATE SFML::Graphics SFML::Window SFML::System)