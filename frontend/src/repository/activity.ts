import { plainToInstance } from 'class-transformer'
import { validate, ValidationError } from 'class-validator'
import { Activity } from '../entity/activity.ts'
import { authFetch } from '../services/auth'

function formatErrors(errors: ValidationError[]): Record<string, string[]> {
    return errors.reduce((acc, err) => {
        acc[err.property] = Object.values(err.constraints ?? {})
        return acc
    }, {} as Record<string, string[]>)
}

export function findAll(filters?: Record<string, any>): Promise<Activity[]> {
    let url = '/api/activities'
    if (filters) {
        const params = new URLSearchParams()
        Object.entries(filters).forEach(([key, value]) => {
            if (value !== undefined && value !== null) {
                params.append(key, String(value))
            }
        })
        url += `?${params.toString()}`
    }

    return authFetch(url)
        .then(res => res.json())
        .then(data => plainToInstance(Activity, data['member'] as object[], {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function findOne(id: number | string): Promise<Activity> {
    return authFetch(`/api/activities/${id}`)
        .then(res => res.json())
        .then(data => plainToInstance(Activity, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function create(activity: Activity): Promise<Activity> {
    return validate(activity, { groups: ['update'] })
        .then(errors => {
            if (errors.length > 0) throw formatErrors(errors)
        })
        .then(() => authFetch('/api/activities', {
            method: 'POST',
            headers: { 'Content-Type': 'application/ld+json' },
            body: JSON.stringify({
                ...activity,
                startDate: new Date(activity.startDate!).toISOString(),
                endDate:   new Date(activity.endDate!).toISOString(),
            }),
        }))
        .then(res => {
            if (!res.ok) throw new Error('Error al crear la actividad')
            return res.json()
        })
        .then(data => plainToInstance(Activity, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function update(id: number | string, activity: Activity): Promise<Activity> {
    return validate(activity, { groups: ['update'] })
        .then(errors => {
            if (errors.length > 0) throw formatErrors(errors)
        })
        .then(() => authFetch(`/api/activities/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/merge-patch+json' },
            body: JSON.stringify({
                ...activity,
                startDate: new Date(activity.startDate!).toISOString(),
                endDate:   new Date(activity.endDate!).toISOString(),
            }),
        }))
        .then(res => {
            if (!res.ok) throw new Error('Error al guardar')
            return res.json()
        })
        .then(data => plainToInstance(Activity, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function remove(id: number | string): Promise<void> {
    return authFetch(`/api/activities/${id}`, {
        method: 'DELETE',
    })
        .then(res => {
            if (!res.ok) throw new Error('Error al eliminar la actividad')
        })
}