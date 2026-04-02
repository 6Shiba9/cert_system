# 🎓 Certificate Management System

A web-based application for managing and generating certificates for university activities.

## 🚀 Features
- Create and manage activities
- Upload participant data (student name & ID)
- Automatically generate certificates
- Search and filter by faculty / major / activity
- Download certificates individually
- Role-based system (Organizer / Student)

## 🛠 Tech Stack
- Laravel (Backend)
- MySQL (Database)
- Bootstrap (Frontend)

## 🎯 Project Overview
This system was developed to simplify the process of managing certificates in university activities.  
It reduces manual work by automating certificate generation and allows students to easily search and download their certificates.

## 🌐 Live Demo
https://certsystemcmru.com/

## 📸 Screenshots
### Dashboard
<img width="1599" height="768" alt="image" src="https://github.com/user-attachments/assets/4f662edf-beff-4cb6-ab04-66f6a7922269" />


### Certificate user
<img width="1586" height="770" alt="image" src="https://github.com/user-attachments/assets/69f798dc-c6de-4311-bdd5-39e80116e2d9" />



## ⚙️ Installation

```bash
git clone https://github.com/6Shiba9/cert_system.git
cd cert_system
composer install
cp .env.example .env
php artisan key:generate
