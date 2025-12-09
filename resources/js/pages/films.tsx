import Layout from '@/components/layout';
import { PersonDetails } from '@/components/personDetails';
import { useState, useEffect, useRef } from 'react';
import { Film, FilmsService } from '@/services/films';
import { FilmDetails } from '@/components/filmDetails';

export default function FilmsDetails({filmId}){
    const initialized = useRef(false);

    const [data, setData] = useState({});
    const filmsService: FilmsService = new FilmsService();
    const fetchData = async (): Promise<void> => {
        const film: Promise<Film> = filmsService.getFilmById(filmId);
        film.then(f => {
            setData(f);
        }).catch(error => console.log(error));
    }

    useEffect(() => {
        if (!initialized.current) {
            initialized.current = true;
            fetchData();
        }
    }, []);

    return (
        <Layout
            mainContent={
                <div className="row align-items-start">
                    <div className="col-2"></div>
                    <div className="col-8">
                        <FilmDetails details={data} />
                    </div>
                    <div className="col-2"></div>
                </div>
            }
        />
    );
}
