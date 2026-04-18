# CodeViz Research Context

> **Note**: This file contains research context from CodeViz. Most recent contexts are at the bottom.

---

## Research Query

map of the code

*Session: f4aab160b7da681201f66b89fbe1e9d9 | Generated: 7/7/2025, 9:35:46 PM*

### Analysis Summary

# Codebase Map: NewSkill2

This report provides a high-level overview of the **NewSkill2** codebase, outlining its primary components, their responsibilities, and their interconnections. The application appears to be a PHP-based social learning platform, featuring user profiles, content creation (posts, classes), communication (chat, notifications), and community interactions (following, reactions).

## High-Level Architecture

The application follows a traditional server-side rendered architecture, primarily using PHP for backend logic and HTML/CSS/JavaScript for the frontend. Data persistence is handled through a database, with interactions managed via PHP scripts.

*   **Core Application Logic**: Handled by various PHP files in the root directory, responsible for routing, data processing, and view rendering.
*   **Styling**: Managed by CSS files located in the [css/](css/) directory.
*   **Assets**: Images and profile photos are stored in [imagenes/](imagenes/) and [fotosPerfil/](fotosPerfil/) respectively.
*   **Shared Components**: Common functionalities like database connection, headers, and footers are encapsulated in the [includes/](includes/) directory.
*   **Third-Party Libraries**: External dependencies like PHPMailer are found in their respective directories, e.g., [PHPMailer/](PHPMailer/).

## Core Components

### 1. User Management & Authentication

This component handles user registration, login, profile management, and password recovery.

*   **Purpose**: To securely manage user accounts and personal information.
*   **Internal Parts**:
    *   **Registration**: [registro.php](registro.php) (user interface) and [guardarNuevoUsuario.php](guardarNuevoUsuario.php) (data processing).
    *   **Login**: [logueo.php](logueo.php).
    *   **Profile Management**:
        *   [perfil.php](perfil.php) and [perfilUsuario.php](perfilUsuario.php) (display user profiles).
        *   [editarPerfil.php](editarPerfil.php), [editarDatos.php](editarDatos.php), [editarinformacion.php](editarinformacion.php) (edit user details).
    *   **Password Recovery**: [recuperar.php](recuperar.php), [procesar_recuperar.php](procesar_recuperar.php), and [cambiar_contrasena.php](cambiar_contrasena.php).
*   **External Relationships**: Interacts with the database via files in [includes/](includes/) (e.g., [includes/conexion.php](includes/conexion.php)) for storing and retrieving user data. Uses [PHPMailer/](PHPMailer/) for sending recovery emails.

### 2. Content Creation & Management

This component allows users to create and manage different types of content, such as posts and classes.

*   **Purpose**: To facilitate user-generated content within the platform.
*   **Internal Parts**:
    *   **Posts**: [crearPost.php](crearPost.php) (creation interface) and [guardarPost.php](guardarPost.php) (saving logic).
    *   **Classes**:
        *   [clases.php](clases.php) (list/overview of classes).
        *   [crear_clase.php](crear_clase.php) (create new classes).
        *   [editar_clase.php](editar_clase.php) (modify existing classes).
        *   [ver_clase.php](ver_clase.php) (view individual class details).
        *   [compartir_clase.php](compartir_clase.php) (share classes).
*   **External Relationships**: Stores content in the database. May involve file uploads via [adjuntar2.php](adjuntar2.php) or [upload2.php](upload2.php).

### 3. Communication & Interaction

This component covers real-time chat, notifications, and social interactions like following and reactions.

*   **Purpose**: To enable user-to-user communication and engagement.
*   **Internal Parts**:
    *   **Chat**:
        *   [chat.php](chat.php) (chat interface).
        *   [enviarMens.php](enviarMens.php), [sendMessage.php](sendMessage.php) (sending messages).
        *   [getMessages.php](getMessages.php), [recibirMens.php](recibirMens.php) (receiving messages).
        *   [buscar_usuarios_ajax.php](buscar_usuarios_ajax.php) (finding users for chat).
    *   **Notifications**: [notificaciones.php](notificaciones.php), [ver_notificaciones.php](ver_notificaciones.php), and [marcar_visto.php](marcar_visto.php).
    *   **Following**: [seguir.php](seguir.php), [seguir_ajax.php](seguir_ajax.php), [verSeguidores.php](verSeguidores.php), and [verSeguidos.php](verSeguidos.php).
    *   **Reactions**: [reaccionar.php](reaccionar.php) and specific reaction scripts like [reaccionarArtes.php](reaccionarArtes.php), [reaccionarDeportes.php](reaccionarDeportes.php), [reaccionarprogra.php](reaccionarprogra.php).
*   **External Relationships**: Heavily relies on AJAX for real-time updates (e.g., [ajax_comentar.php](ajax_comentar.php)). Interacts with the database for storing messages, notifications, and follow/reaction data.

### 4. Search & Discovery

This component provides functionality for users to search for content and other users.

*   **Purpose**: To help users find relevant information and connect with others.
*   **Internal Parts**: [buscar.php](buscar.php) and [busqueda.php](busqueda.php).
*   **External Relationships**: Queries the database to retrieve matching content or user profiles.

### 5. Shared Utilities & Configuration

This component contains common code snippets, database connection details, and general configurations.

*   **Purpose**: To provide reusable functions and centralize configurations, promoting code reusability and maintainability.
*   **Internal Parts**:
    *   **Database Connection**: [includes/conexion.php](includes/conexion.php), [includes/dbconexion.php](includes/dbconexion.php), [includes/PDOdb.php](includes/PDOdb.php).
    *   **Session Management**: [includes/session.php](includes/session.php).
    *   **Headers & Footers**: [includes/header.php](includes/header.php), [includes/footer.php](includes/footer.php), [includes/head.php](includes/head.php), [includes/headerPerfil.php](includes/headerPerfil.php).
    *   **Insignias Helper**: [includes/insignias_helper.php](includes/insignias_helper.php).
*   **External Relationships**: These files are included by various PHP scripts throughout the application to establish database connections, manage user sessions, and render consistent UI elements.

### 6. Static Assets & Styling

This component manages all visual assets and styling rules for the application.

*   **Purpose**: To define the look and feel of the application.
*   **Internal Parts**:
    *   **CSS Files**: Located in [css/](css/), with specific files for different sections (e.g., [css/inicio.css](css/inicio.css), [css/perfil.css](css/perfil.css)).
    *   **General Images**: Stored in [imagenes/](imagenes/) (e.g., [imagenes/logo NewSkill2.png](imagenes/logo%20NewSkill2.png), [imagenes/hogar.png](imagenes/hogar.png)).
    *   **Profile Photos**: Stored in [fotosPerfil/](fotosPerfil/) (e.g., [fotosPerfil/LogoNS.png](fotosPerfil/LogoNS.png)).
    *   **Insignia Images**: Stored in [Insignias/](Insignias/) (e.g., [Insignias/aprendiz_dedicado_insignia.png](Insignias/aprendiz_dedicado_insignia.png)).
*   **External Relationships**: Linked directly from HTML files to apply styles and display images.

### 7. Third-Party Libraries

*   **PHPMailer**: Located in [PHPMailer/](PHPMailer/), used for sending emails, likely for password recovery or notifications.

This map provides a foundational understanding of the NewSkill2 codebase, highlighting its modular structure and the responsibilities of its key components.

