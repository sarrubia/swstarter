import SearchResultsItem from '@/components/searchResultsItem';

export default function SearchResults({ data, isLoading, error }) {
    function Error() {
        if (error !== null && error !== undefined && error !== '') {
            return <p>{error}</p>;
        }

        return <span></span>;
    }

    function Loading() {
        if (isLoading) {
            return (
                <div className="card-body-align-vertical">
                    <div className="row">
                        <div className="color-gray text-bold col-12">Searching...</div>
                    </div>
                </div>
            );
        }

        return <p></p>;
    }

    function Results() {
        if (isLoading) {
            return <div></div>;
        }

        if (data && data.length > 0) {
            return (
                <div className="row">
                    <div className="col-12">
                        {data.map((item) => (
                            <SearchResultsItem item={item} />
                        ))}
                    </div>
                </div>
            );
        } else {
            return (
                <div className="card-body-align-vertical">
                    <div className="row align-items-end">
                        <div className="color-gray text-bold col-12">
                            <p>There are zero matches.</p>
                            <p>Use the form to search for people or movies</p>
                        </div>
                    </div>
                </div>
            );
        }
    }

    return (
        <div className="card search-results">
            <div className="card-body">
                <div className="row">
                    <div className="col-12">
                        <div className="row search-results-title">
                            <div className="col-12">
                                <h2>Results:</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <Results />
                <Loading />
                <Error />
            </div>
        </div>
    );
}
