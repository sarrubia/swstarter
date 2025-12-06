import Layout from '@/components/layout';
import PersonDetails from '@/components/personDetails';
import { PeopleService, Person } from '@/services/people';
import { useState, useEffect } from 'react';

export default function PeopleDetails({personId}){
    const [data, setData] = useState({});
    const peopleService: PeopleService = new PeopleService();
    const fetchData = async (): Promise<void> => {

        const person: Promise<Person> = peopleService.getPeopleById(personId);
        person.then(p => {
            console.log(p);
            setData(p);
        }).catch(error => console.log(error));
    }

    useEffect(() => {
        fetchData();
    }, []);

    return (
        <Layout
            mainContent={
                <div className="row align-items-start">
                    <div className="col-2"></div>
                    <div className="col-8">
                        <PersonDetails details={data} />
                    </div>
                    <div className="col-2"></div>
                </div>
            }
        />
    );
}
