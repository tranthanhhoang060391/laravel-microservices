# Laravel Microservices

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Architecture](#architecture)
- [Installation](#installation)

## Introduction

This project is a [microservices/monolithic]-based web application built using Laravel. It is designed to [briefly describe the purpose of the application, e.g., manage products and orders in an e-commerce platform]. The application leverages various Laravel packages and tools such as Laravel Sanctum, Laravel Octane, and RabbitMQ for secure communication, performance optimization, and asynchronous processing.

## Features

- User authentication with Laravel Sanctum
- Product management (CRUD operations)
- Order management (CRUD operations)
- RabbitMQ integration for asynchronous communication
- High performance using Laravel Octane
- API Gateway for routing and security

## Technologies Used

- **Laravel Framework**: PHP framework for web application development.
- **Laravel Sanctum**: Token-based authentication for securing APIs.
- **Laravel Octane**: High-performance server for handling concurrent requests.
- **RabbitMQ**: Message broker for asynchronous communication.
- **Docker**: Containerization of services for a consistent development environment.
- **Apache JMeter**: Load testing tool for performance benchmarking.

## Architecture

The application follows a microservices architecture, with separate services for managing products, orders, and user authentication. Each service is built as an independent Laravel application with its own database and logic, communicating through a central API Gateway.

## Installation

### Prerequisites

- Docker and Docker Compose
- PHP 8.x
- Composer
