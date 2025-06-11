import React from 'react'

function DefiBox(props) {

    const [data, setData] = React.useState(props.data)

    return (
    <>
        <div key={data.index} className="post">
            <h2>{data.nom}</h2>
            <p>{data.description}</p>
            <p>Reward Points: {data.pointsRecompense}</p>
            <p>Difficulty: {data.difficulte}</p>
            <p>User: {data.user}</p>
            <p>Tags: {data.tags.join(', ')}</p>
        </div>
    </>
    );
}

export default DefiBox;