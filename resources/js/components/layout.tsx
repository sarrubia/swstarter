import SearchBox from '@/components/searchBox';
import SearchResults from '@/components/searchResults';


export default function Layout({ mainContent }) {
    return (
        <div>
            <nav className="navbar bg-body-tertiary navbar-swstarter">
                <div className="container-fluid">
                    <span className="navbar-brand h1 mb-0">SWStarter</span>
                </div>
            </nav>
            <div className="search-container container text-center">
                <main>{mainContent}</main>
            </div>
        </div>
    );
}
