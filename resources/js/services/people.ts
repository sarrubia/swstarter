import App from '@/actions/App';
import type { AxiosInstance, AxiosResponse } from 'axios';
import axios from 'axios';

export interface Person {
    uid: string;
    name: string;
    gender: string;
    skin_color: string;
    hair_color: string;
    height: string;
    eye_color: string;
    mass: string;
    birth_year: string;
}
export class PeopleService {
    private api: AxiosInstance;

    constructor(/*baseURL: string*/) {
        this.api = axios.create({
            // baseURL,
            headers: {
                'Content-Type': 'application/json',
            },
        });
    }

    public async getPeople(name: string): Promise<Person[]> {
        try {
            const response: AxiosResponse<Person[]> = await this.api.get(`${App.Http.Controllers.PeopleController.get().url}?name=${name}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching people:', error);
            throw error;
        }
    }
}
