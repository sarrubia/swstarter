import { Link } from '@inertiajs/react';
import { Film, FilmCharacters } from '@/services/films';
import { backendLink2frontendLink } from '@/lib/utils';

interface FilmDetails {
    details: Film;
}

type FilmDetailsProps = FilmDetails


interface FilmCharactersProps {
    characters: FilmCharacters[];
}

type FilmCharactersPropsType = FilmCharactersProps;
export function FilmDetails({ details } : FilmDetailsProps) {

    function CharactersLink({characters}: FilmCharactersPropsType) {

        if (!characters) {
            return (
                <div></div>
            );
        }

        return (
            <div className="person-details-films">
                {characters.map((character: FilmCharacters, index: number, original: FilmCharacters[]) => {
                    const isLastItem = index === original.length - 1;
                    return (
                        <span><a href={backendLink2frontendLink(character.link)} key={character.uid}>{character.name}</a>{!isLastItem && <span>, </span>}</span>
                    )
                })}
            </div>
        );

    }


    console.log(details);
    return (
        <div className="card search-results">
            <div className="card-body search-results-details-body">
                <div className="row">
                    <div className="col-12">
                        <div className="row search-results-details-title">
                            <div className="col-12">
                                <h2>{details.title}</h2>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-5 search-results-details">
                                <h2>Opening Crawl</h2>
                                <p>{details.opening_crawl}
                                </p>
                            </div>
                            <div className="col-2"></div>
                            <div className="col-5 search-results-details">
                                <h2>Characters</h2>
                                {details.characters && <CharactersLink characters={details.characters} />}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="row search-results-details-body-last-row">
                    <div className="col-6 ">
                        <Link type="button" href={`/`} className="btn btn-success btn-swstarter">
                            BACK TO SEARCH
                        </Link>
                    </div>
                    <div className="col-6"></div>
                </div>
            </div>
        </div>
    );
}
