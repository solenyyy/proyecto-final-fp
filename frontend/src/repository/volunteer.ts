import { plainToInstance } from 'class-transformer'
import { validate, ValidationError } from 'class-validator'
import { Volunteer } from '../entity/volunteer'

function formatErrors(errors: ValidationError[]): Record<string, string[]> {
    return errors.reduce((acc, err) => {
        acc[err.property] = Object.values(err.constraints ?? {})
        return acc
    }, {} as Record<string, string[]>)
}

export function findAll(): Promise<Volunteer[]> {
    return fetch('/api/volunteers')
        .then(res => res.json())
        .then(data => plainToInstance(Volunteer, data['member'] as object[], {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function findOne(id: number | string): Promise<Volunteer> {
    return fetch(`/api/volunteers/${id}`)
        .then(res => res.json())
        .then(data => plainToInstance(Volunteer, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function create(volunteer: Volunteer): Promise<Volunteer> {
    return validate(volunteer, { groups: ['update'] })
        .then(errors => {
            if (errors.length > 0) throw formatErrors(errors)
        })
        .then(() => fetch('/api/volunteers', {
            method: 'POST',
            headers: { 'Content-Type': 'application/ld+json' },
            body: JSON.stringify({
                ...volunteer,
                birthDate: new Date(volunteer.birthDate!).toISOString(),
            }),
        }))
        .then(res => {
            if (!res.ok) throw new Error('Error al crear el voluntario')
            return res.json()
        })
        .then(data => plainToInstance(Volunteer, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}

export function update(id: number | string, volunteer: Volunteer): Promise<Volunteer> {
    return validate(volunteer, { groups: ['update'] })
        .then(errors => {
            if (errors.length > 0) throw formatErrors(errors)
        })
        .then(() => fetch(`/api/volunteers/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/merge-patch+json' },
            body: JSON.stringify({
                ...volunteer,
                birthDate: new Date(volunteer.birthDate!).toISOString(),
            }),
        }))
        .then(res => {
            if (!res.ok) throw new Error('Error al guardar')
            return res.json()
        })
        .then(data => plainToInstance(Volunteer, data, {
            groups: ['default'],
            excludeExtraneousValues: true
        }))
}