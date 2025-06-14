import React from "react"

export default function Lobby(props) {
    React.useEffect(() => {
        props.setShowLeftNavigation(true)
        props.setShowLeaderboard(true)
    },[])

    return (
        <>
        
        </>
    )
}