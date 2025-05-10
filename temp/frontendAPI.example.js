// Beispielkonfiguration für die API-Aufrufe im Frontend

// Basis-URL des Backends
const API_BASE_URL = 'https://cookieshield-backend-main-zdejjv.laravel.cloud/api';

// Funktion zum Registrieren eines Benutzers
async function registerUser(userData) {
  try {
    const response = await fetch(`${API_BASE_URL}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(userData),
      credentials: 'include',
    });
    
    if (!response.ok) {
      throw new Error(`HTTP Error ${response.status}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error('Registration failed:', error);
    throw error;
  }
}

// Funktion zum Einloggen eines Benutzers
async function loginUser(credentials) {
  try {
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(credentials),
      credentials: 'include',
    });
    
    if (!response.ok) {
      throw new Error(`HTTP Error ${response.status}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error('Login failed:', error);
    throw error;
  }
}

// Funktion zum Abrufen der Cookie-Einstellungen
async function getCookieSettings() {
  try {
    const response = await fetch(`${API_BASE_URL}/cookie-settings`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
      credentials: 'include',
    });
    
    if (!response.ok) {
      throw new Error(`HTTP Error ${response.status}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error('Failed to fetch settings:', error);
    throw error;
  }
}

// Funktion zum Aktualisieren der Cookie-Einstellungen
async function updateCookieSettings(settings) {
  try {
    const response = await fetch(`${API_BASE_URL}/cookie-settings`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
      body: JSON.stringify(settings),
      credentials: 'include',
    });
    
    if (!response.ok) {
      throw new Error(`HTTP Error ${response.status}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error('Failed to save settings:', error);
    throw error;
  }
}

export { registerUser, loginUser, getCookieSettings, updateCookieSettings }; 