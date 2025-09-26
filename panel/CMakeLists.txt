cmake_minimum_required(VERSION 3.10)

project(panel)

find_package(SFML COMPONENTS System Window Graphics Network Audio CONFIG REQUIRED)
find_package(nlohmann_json CONFIG REQUIRED)
find_package(CURL CONFIG REQUIRED)

file(GLOB_RECURSE SOURCES "src/*.cpp")
add_executable(panel ${SOURCES})

target_link_libraries(panel PRIVATE SFML::Graphics SFML::Window SFML::System nlohmann_json::nlohmann_json CURL::libcurl)
target_compile_features(panel PRIVATE cxx_std_17)
target_include_directories(panel PRIVATE src)