<a name="readme-top">

<br/>

<br />
<div align="center">
  <a href="https://github.com/BorisDelaCruz/">
  <!-- TODO: If you want to add logo or banner you can add it here -->
  </a>
<!-- TODO: Change Title to the name of the title of your Project -->
  <h3 align="center">AD-Task-3</h3>
</div>
<!-- TODO: Make a short description -->
<div align="center">
  Part 1
</div>

<br />

<!-- TODO: Change the zyx-0314 into your github username  -->
<!-- TODO: Change the WD-Template-Project into the same name of your folder -->

![](https://visit-counter.vercel.app/counter.png?page=zyx-0314/AD-CI4-Template-Project)

[![wakatime](https://wakatime.com/badge/user/018dd99a-4985-4f98-8216-6ca6fe2ce0f8/project/63501637-9a31-42f0-960d-4d0ab47977f8.svg)](https://wakatime.com/badge/user/018dd99a-4985-4f98-8216-6ca6fe2ce0f8/project/63501637-9a31-42f0-960d-4d0ab47977f8)

---

<br />
<br />

<!-- TODO: If you want to add more layers for your readme -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#overview">Overview</a>
      <ol>
        <li>
          <a href="#key-components">Key Components</a>
        </li>
        <li>
          <a href="#technology">Technology</a>
        </li>
      </ol>
    </li>
    <li>
      <a href="#rule,-practices-and-principles">Rules, Practices and Principles</a>
    </li>
    <li>
      <a href="#resources">Resources</a>
    </li>
  </ol>
</details>

---

## Overview

<!-- TODO: To be changed -->
<!-- The following are just sample -->

Project Overview:

AD-Task-3 is a Project Management System built with PHP that integrates both PostgreSQL and MongoDB databases. It provides authentication, user management, and project tracking capabilities

### Key Components

<!-- TODO: List of Key Components -->
<!-- The following are just sample -->

- Authentication & Authorization
- CRUD Operations for Invetory System

### Technology

<!-- TODO: List of Technology Used -->
#### Language
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

#### Framework/Library
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

#### Databases
![MySQL](https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white)
![MongoDB](https://img.shields.io/badge/MongoDB-47A248?style=for-the-badge&logo=mongodb&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-336791?style=for-the-badge&logo=postgresql&logoColor=white)


## Rules, Practices and Principles

<!-- Do not Change this -->

1. Always use `AD-` in the front of the Title of the Project for the Subject followed by your custom naming.
2. Do not rename `.php` files if they are pages; always use `index.php` as the filename.
3. Add `.component` to the `.php` files if they are components code; example: `footer.component.php`.
4. Add `.util` to the `.php` files if they are utility codes; example: `account.util.php`.
5. Place Files in their respective folders.
6. Different file naming Cases
   | Naming Case | Type of code         | Example                           |
   | ----------- | -------------------- | --------------------------------- |
   | Pascal      | Utility              | Accoun.util.php                   |
   | Camel       | Components and Pages | index.php or footer.component.php |
8. Renaming of Pages folder names are a must, and relates to what it is doing or data it holding.
9. Use proper label in your github commits: `feat`, `fix`, `refactor` and `docs`
10. File Structure to follow below.

```
AD-Task-3
└─ assets
|   └─ css
|   |   └─ style.css
|   └─ img
|   |   └─ nyebe_white.png
|   └─ js
|       └─ script.js
└─ components
|   └─ alert.component.php
|   └─ footer.component.php
|   └─ header.component.php
|   └─ templates
|      └─ dashboard.component.php
└─ handlers
|   └─ auth.handler.php
|   └─ mongodbChecker.handler.php
|   └─ postgreChecker.handler.php
└─ layouts
|   └─ main.layout.php
|   └─ style.css
└─ pages
|  └─ dashboard
|  |  └─ assets
|  |  |  └─ style.css
|  |  |  └─ script.js
|  |  └─ index.php
|  └─ ExamplePage
|  |  └─ assets
|  |  |  └─ css
|  |  |  |  └─ style.css
|  |  |  └─ img
|  |  |  |  └─ nyebe_white.png
|  |  |  └─ js
|  |  |     └─ script.js
|  |  └─ index.php
|  └─ login
|  |  └─ assets
|  |  |  └─ style.css
|  |  |  └─ script.js
|  |  └─ index.php
|  └─ logout
|  |  └─ assets
|  |  |  └─ style.css
|  |  |  └─ script.js
|  |  └─ index.php
|  └─ signup
|     └─ assets
|     |  └─ style.css
|     |  └─ script.js
|     └─ index.php
└─ sql
|  └─ database.sql
|  └─ migrate.sql
|  └─ resetdb.sql
|  └─ seed.sql
└─ staticDatas
|  └─ appConfig.staticData.php
|  └─ databaseConnection.staticData.php
|  └─ users.staticData.php
└─ utils
|   └─ auth.util.php
|   └─ envSetter.util.php
|   └─ htmlescape.util.php
└─ vendor
|    └─ composer
|    |      └─ autoload_classmap.php
|    |      └─ autoload_namespaces.php
|    |      └─ autoload_psr4.php
|    |      └─ autoload_real.php
|    |      └─ autoload_static.php
|    |      └─ ClassLoader.php
|    |      └─ installed.json
|    |      └─ installed.php
|    |      └─ installedVersions.php
|    |      └─ LICENSE
|    └─ autoload.php
└─ .gitignore
└─ bootstrap.php
└─ composer.json
└─ composer.lock
└─ index.php
└─ readme.md
└─ router.php
```
> The following should be renamed: name.css, name.js, name.jpeg/.jpg/.webp/.png, name.component.php(but not the part of the `component.php`), Name.utils.php(but not the part of the `utils.php`)

## Resources


