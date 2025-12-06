import { Link } from '@inertiajs/react';


export default function PersonDetails({details}) {
console.log(details);
    return(
        <div className="card search-results">
            <div className="card-body">
                <div className="row">
                    <div className="col-12">
                        <div className="row search-results-title">
                            <div className="col-12">
                                <h2>{details.name}</h2>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-6">
                                <h2>Details</h2>
                                <ul>
                                    <li>Birth Year: {details.birth_year}</li>
                                    <li>Eye Color: {details.eye_color}</li>
                                    <li>Gender: {details.gender}</li>
                                    <li>Hair Color: {details.hair_color}</li>
                                    <li>Height: {details.height}</li>
                                    <li>Mass: {details.mass}</li>
                                    <li>Skin Color: {details.skin_color}</li>
                                </ul>
                            </div>
                            <div className="col-6">
                                <h2>Movies</h2>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-6">
                                <Link type="button" href={`/`} className="btn btn-success" >
                                    BACK TO SEARCH
                                </Link>
                            </div>
                            <div className="col-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
