# Child Growth Monitoring System (IoT-Based Web Application)

## Overview
This project is a web-based child growth monitoring system developed as a final academic project.  
The system is designed to receive body weight data from a load cell sensor (HX711), store the data in a database, and display growth information through a web-based dashboard.

The application was developed using the Laravel framework and MySQL database.  
During development, the system was tested using real sensor data. This repository focuses on the software implementation, system architecture, and testing results.

---

## System Architecture
The system consists of three main components:
1. Data acquisition using HX711 load cell sensor
2. Backend system built with Laravel
3. Web-based user interface for monitoring and visualization

---

## Data Flow
1. The HX711 sensor measures the child’s body weight.
2. The microcontroller sends the data to the backend server using HTTP requests.
3. The backend processes and stores the data in the database.
4. The data is displayed on the web dashboard for monitoring purposes.

---
## System Testing

System testing was conducted to ensure that the application functions correctly
from data input to data visualization.

### Dashboard Display Test
The dashboard was tested to verify that monitoring data is displayed correctly
and updated according to the stored database records.

### Add Child Data Test
This test ensures that new child data can be successfully input through the
web form and validated by the backend system.

### Data Storage and Child Growth History Test
The system was tested to confirm that submitted data is correctly stored in the
MySQL database to ensure that historical data for each child can beretrieved and displayed accurately.

---

## HX711 Sensor Integration
During the development phase, the system was integrated and tested with a real HX711 load cell sensor connected to a microcontroller.

The hardware source code is not included in this repository. However, system architecture, backend endpoints, and testing results are provided as evidence of successful integration between hardware and software components.

---

## Technologies Used
- Backend: Laravel
- Frontend: Blade Template, HTML, CSS, JavaScript
- Database: MySQL
- IoT Sensor: HX711 Load Cell
- Web Server: Apache (XAMPP)
- Package Manager: Composer, NPM

---

## Installation & Setup

### Prerequisites
- PHP >= 8.0
- Composer
- Node.js & NPM
- MySQL
- XAMPP (recommended)

### Installation Steps
```bash
git clone https://github.com/your-username/your-repository-name.git
cd your-repository-name
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your database credentials in the .env file, then run:
```bash
php artisan migrate
php artisan serve
```
Access the application at:
http://localhost:8000

### Notes
This repository focuses on the software implementation of the system.
Real child data is not included for privacy and ethical reasons.
Sensor data can be simulated if hardware is unavailable.
