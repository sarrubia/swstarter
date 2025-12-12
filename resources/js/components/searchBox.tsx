import { ChangeEvent, useState } from 'react';

export default function SearchBox({ onClick, isLoading }) {
    const [emptyImputValue, setEmptyImputValue] = useState(true);
    const [inputValue, setInputValue] = useState('');
    const [inputPlaceholderValue, setInputPlaceholderValue] = useState('e.g. Chewbacca, Yoda, Boba Fett');
    const [radioValue, setRadioValue] = useState('people'); // Set people as default value
    const handleClick = () => {
        try {
            onClick(inputValue, radioValue);
        } catch (e: unknown) {
            console.error(e);
        }
    };

    const handleInputChange = (e: ChangeEvent<HTMLInputElement>) => {
        setInputValue(e.target.value)
        if(e.target.value.length > 0) {
            setEmptyImputValue(false);
        } else {
            setEmptyImputValue(true);
        }
    }

    const handleRadioChange = (e: ChangeEvent<HTMLInputElement>) => {
        setRadioValue(e.target.value)
        if(e.target.value === 'people') {
            setInputPlaceholderValue('e.g. Chewbacca, Yoda, Boba Fett');
        } else {
            setInputPlaceholderValue('e.g. A New Hope, The Empire Strikes Back');
        }
    }

    return (
        <div>
            <div className="card">
                <div className="card-body">
                    <form>
                        <div className="row mb-3">
                            <div className="col">
                                <p>What are you searching for?</p>
                            </div>
                        </div>

                        <div className="row mb-3">
                            <div className="col">
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="inlineRadioOptions"
                                        id="radioPeople"
                                        value="people"
                                        checked={radioValue === 'people'}
                                        onChange={handleRadioChange}
                                    />
                                    <label className="form-check-label" htmlFor="radioPeople">
                                        People
                                    </label>
                                </div>
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="inlineRadioOptions"
                                        id="radioFilms"
                                        value="films"
                                        checked={radioValue === 'films'}
                                        onChange={handleRadioChange}
                                    />
                                    <label className="form-check-label" htmlFor="radioFilms">
                                        Movies
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div className="row mb-3">
                            <div className="col">
                                <div className="form-text">
                                    <input
                                        type="text"
                                        className="form-control"
                                        id="searchText"
                                        placeholder={inputPlaceholderValue}
                                        value={inputValue}
                                        onChange={handleInputChange}
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="row mb-3">
                            <div className="col">
                                <div className="d-grid gap-2">
                                    <button type="button" className="btn btn-swstarter" onClick={handleClick} disabled={isLoading || emptyImputValue}>
                                        {isLoading ? 'SEARCHING...' : 'SEARCH'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
