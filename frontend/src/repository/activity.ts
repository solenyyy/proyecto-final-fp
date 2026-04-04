import { plainToInstance } from 'class-transformer'
import { validate } from 'class-validator'
import { Activity } from '../entity/activity.ts'

export function findAll(): Promise<Activity[]> {
    return fetch('/api/activities')
        .then(res => res.json())
        .then(data => plainToInstance(Activity, data['member'] as object[], {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function findOne(id: number | string): Promise<Activity> {
    return fetch(`/api/activities/${id}`)
        .then(res => res.json())
        .then(data => plainToInstance(Activity, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function update(id: number | string, activity: Activity): Promise<Activity> {
    return validate(activity, { groups: ['update'] })
        .then(errors => {
            if (errors.length > 0) throw new Error(
                Object.values(errors[0].constraints ?? {})[0]
            )
        })
        .then(() => fetch(`/api/activities/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/merge-patch+json' },
            body: JSON.stringify(activity),
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