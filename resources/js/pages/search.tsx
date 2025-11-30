import SearchBox from '@/components/searchBox';
import SearchResults from '@/components/searchResults';
import { Film, FilmsService } from '@/services/films';
import { PeopleService, Person } from '@/services/people';
import { useState } from 'react';

export default function Search() {
    const [data, setData] = useState({});
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const filmsService: FilmsService = new FilmsService();
    const peopleService: PeopleService = new PeopleService();
    const fetchData = async (textToSearch: string, apiToCall: string) => {
        if (!textToSearch || !apiToCall) {
            setError('Please enter a text to search or choose between Movie or People.');
            return;
        }

        setLoading(true);
        setError('');
        try {
            if (apiToCall === 'people') {
                const people: Promise<Person[]> = peopleService.getPeople(textToSearch);
                people
                    .then((f) => {
                        setData(f);
                    })
                    .finally(() => setLoading(false));
            } else {
                const films: Promise<Film[]> = filmsService.getFilms(textToSearch);
                films
                    .then((f) => {
                        setData(f);
                    })
                    .catch((reason) => {
                        console.log(reason);
                        throw new Error(`error fetching ${textToSearch}!`);
                    })
                    .finally(() => setLoading(false));
            }
        } catch (e: unknown) {
            if (e instanceof Error) {
                setError(e.message);
            } else {
                setError(`Error= ${e}`);
            }
        }
    };

    return (
        <div>
            <nav className="navbar bg-body-tertiary navbar-swstarter">
                <div className="container-fluid">
                    <span className="navbar-brand h1 mb-0">SWStarter</span>
                </div>
            </nav>
            <div className="search-container container text-center">
                <div className="row align-items-start">
                    <div className="col-4">
                        <SearchBox onClick={fetchData} isLoading={loading} />
                    </div>
                    <div className="col-8">{data && <SearchResults data={data} isLoading={loading} error={error} />}</div>
                </div>
            </div>
        </div>
    );
}
