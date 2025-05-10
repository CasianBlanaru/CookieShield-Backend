// API-Konfiguration für das Frontend

// Basis-URL des Backends - Direkt auf die Laravel-API verweisen statt auf einen Proxy
// Keine doppelten /api-Pfade mehr
const API_BASE_URL = 'https://cookieshield-backend-main-zdejjv.laravel.cloud/api';

// Funktion zum Abrufen des CSRF-Tokens (falls benötigt)
async function getCsrfToken() {
  try {
    const response = await fetch(`${API_BASE_URL}/csrf-token`, {
      method: 'GET',
      credentials: 'include',
    });
    
    if (!response.ok) {
      throw new Error(`HTTP Error ${response.status}`);
    }
    
    const data = await response.json();
    return data.token;
  } catch (error) {
    console.error('Failed to fetch CSRF token:', error);
    return null;
  }
}

// Funktion zum Registrieren eines Benutzers
async function registerUser(userData) {
  try {
    // Optional: CSRF-Token abrufen, falls die API dies erfordert
    // const csrfToken = await getCsrfToken();
    
    const response = await fetch(`${API_BASE_URL}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        // 'X-CSRF-TOKEN': csrfToken,
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
    // Optional: CSRF-Token abrufen, falls die API dies erfordert
    // const csrfToken = await getCsrfToken();
    
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        // 'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify(credentials),
      credentials: 'include',
    });
    
    if (!response.ok) {
      throw new Error(`HTTP Error ${response.status}`);
    }
    
    const data = await response.json();
    
    // Token im localStorage speichern
    if (data.token) {
      localStorage.setItem('token', data.token);
    }
    
    return data;
  } catch (error) {
    console.error('Login failed:', error);
    throw error;
  }
}

// Funktion zum Abrufen der Cookie-Einstellungen
async function getCookieSettings() {
  try {
    const token = localStorage.getItem('token');
    
    if (!token) {
      throw new Error('No authentication token found');
    }
    
    const response = await fetch(`${API_BASE_URL}/cookie-settings`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
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
    const token = localStorage.getItem('token');
    
    if (!token) {
      throw new Error('No authentication token found');
    }
    
    const response = await fetch(`${API_BASE_URL}/cookie-settings`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
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

// Funktion zum Ausloggen eines Benutzers
function logout() {
  localStorage.removeItem('token');
  // Weitere Aufräumaufgaben hier hinzufügen
}

export { 
  registerUser, 
  loginUser, 
  getCookieSettings, 
  updateCookieSettings,
  logout,
  getCsrfToken
}; 