import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function backendLink2frontendLink(link: string): string {
    const toRemove = "/api";
    return link.replace(new RegExp(`^${toRemove}`), "");
}
