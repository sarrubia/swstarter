import App from '@/actions/App';
import type { AxiosInstance, AxiosResponse } from 'axios';
import axios from 'axios';

export interface Film {
    uid: string;
    producer: string;
    title: string;
    director: string;
    release_date: string;
    opening_crawl: string;
    episode_id: string;
}

export class FilmsService {
    private api: AxiosInstance;

    constructor(/*baseURL: string*/) {
        this.api = axios.create({
            // baseURL,
            headers: {
                'Content-Type': 'application/json',
            },
        });
    }

    public async getFilms(title: string): Promise<Film[]> {
        try {
            const response: AxiosResponse<Film[]> = await this.api.get(`${App.Http.Controllers.FilmsController.get().url}?title=${title}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching movies:', error);
            throw error;
        }
    }
}
