import { Film } from '@/services/films';
import { Person } from '@/services/people';
import { Link } from '@inertiajs/react';

interface SearchResultsItemPropsFilm {
    item: Film;
    setDetails;
}

interface SearchResultsItemPropsPeople {
    item: Person;
    setDetails;
}

type SearchProps = SearchResultsItemPropsFilm | SearchResultsItemPropsPeople;

// Type Guard for Film
function isFilm(item: Film | Person): item is Film {
    // Check if the item has the 'title' property AND if 'title' is a string
    return (item as Film).title !== undefined && typeof (item as Film).title === 'string';
}

export default function SearchResultsItem({ item }: SearchProps) {
    function onClick(item: Film | Person) {
        const msg = `Screen not implemented.\nThe clicked object is: ${JSON.stringify(item)}`;
        alert(msg);
    }

    return (
        <div className="row align-items-center search-results-item" key={item.uid}>
            <div className="col-8">
                <h3 className="search-results-item-title">{isFilm(item) ? item.title : item.name}</h3>
            </div>
            <div className="col-4">
                <button type="button" className="btn btn-success" onClick={() => onClick(item)}>
                    SEE DETAILS
                </button>

                {/*This one implements the Link to navigate to the people description screen*/}
                <Link type="button" href={`/${isFilm(item) ? 'films' : 'people'}/${item.uid}`} className="btn btn-success" >
                    SEE DETAILS Link
                </Link>
            </div>
        </div>
    );
}
