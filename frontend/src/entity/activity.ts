import { Expose, Transform } from 'class-transformer'
import { IsString, IsNotEmpty, IsEnum, IsOptional, IsDateString } from 'class-validator'

export enum Collective {
    CIUDADANOS = 'ciudadanos',
    MAYORES    = 'mayores',
    JOVENES    = 'jovenes',
    MUJERES    = 'mujeres',
}

export class Activity {
    @Expose({ groups: ['default'] })
    public id: number | null = null

    @Expose({ groups: ['default'] })
    @IsNotEmpty({ message: 'El nombre es obligatorio', groups: ['update'] })
    public name: string | null = null

    @Expose({ groups: ['default'] })
    public description: string | null = null

    @Expose({ groups: ['default'] })
    @IsNotEmpty({ message: 'La fecha de inicio es obligatoria', groups: ['update'] })
    public startDate: string | null = null

    @Expose({ groups: ['default'] })
    @IsNotEmpty({ message: 'La fecha de fin es obligatoria', groups: ['update'] })
    public endDate: string | null = null

    @Expose({ groups: ['default'] })
    @IsEnum(Collective, { message: 'Colectivo no válido', groups: ['update'] })
    public collective: Collective | null = null

    @Expose({ groups: ['default'] })
    @IsOptional({ groups: ['update'] })
    @Transform(({ value }) => {
        if (typeof value === 'object' && value !== null) return value['@id'] ?? null
        return value ?? null
    })
    public volunteer: string | null = null
}