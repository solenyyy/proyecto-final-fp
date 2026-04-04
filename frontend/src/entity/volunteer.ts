import { Expose } from 'class-transformer'
import { IsString, IsNotEmpty, IsOptional, IsEmail, IsDateString } from 'class-validator'

export class Volunteer {
    @Expose({ groups: ['default'] })
    public id: number | null = null

    @Expose({ groups: ['default'] })
    @IsString({ groups: ['update'] })
    @IsNotEmpty({ message: 'El nombre es obligatorio', groups: ['update'] })
    public name: string | null = null

    @Expose({ groups: ['default'] })
    @IsEmail({}, { message: 'El email no es válido', groups: ['update'] })
    @IsOptional({ groups: ['update'] })
    public email: string | null = null

    @Expose({ groups: ['default'] })
    @IsString({ groups: ['update'] })
    @IsNotEmpty({ message: 'El DNI o NIE es obligatorio', groups: ['update'] })
    public dniNie: string | null = null

    @Expose({ groups: ['default'] })
    @IsString({ groups: ['update'] })
    @IsOptional({ groups: ['update'] })
    public bio: string | null = null

    @Expose({ groups: ['default'] })
    @IsDateString({}, { groups: ['update'] })
    @IsNotEmpty({ message: 'La fecha de nacimiento es obligatoria', groups: ['update'] })
    public birthDate: string | null = null

    @Expose({ groups: ['default'] })
    public activitiesCount: number | null = null
}