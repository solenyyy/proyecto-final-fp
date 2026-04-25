const API_URL = import.meta.env.VITE_API_URL ?? ''

export interface LoginResponse {
    token: string
}

export function authFetch(url: string, options: RequestInit = {}): Promise<Response> {
    const token = getToken()
    return fetch(`${API_URL}${url}`, {
        ...options,
        headers: {
            ...options.headers,
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
        },
    })
}

export function login(email: string, password: string): Promise<LoginResponse> {
    return fetch(`${API_URL}/api/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    })
        .then(res => {
            if (!res.ok) throw new Error('Credenciales incorrectas')
            return res.json()
        })
}

export function logout(): void {
    localStorage.removeItem('token')
}

export function getToken(): string | null {
    return localStorage.getItem('token')
}

export function isAuthenticated(): boolean {
    return !!getToken()
}