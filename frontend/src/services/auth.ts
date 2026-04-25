export interface LoginResponse {
    token: string
}

export function authFetch(url: string, options: RequestInit = {}): Promise<Response> {
    const token = getToken()
    return fetch(url, {
        ...options,
        headers: {
            ...options.headers,
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
        },
    })
}

export function login(email: string, password: string): Promise<LoginResponse> {
    return fetch('/api/login', {
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