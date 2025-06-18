import React, { useState, useEffect } from "react";
import {
  Accordion,
  AccordionBody,
  AccordionHeader,
  AccordionItem,
  Button,
  Placeholder,
  Input,
  Modal,
  ModalHeader,
  ModalBody,
  ModalFooter,
  FormGroup,
  Label
} from "reactstrap";

export default function Profil(props) {
  // États pour l'accordéon + chargement
  const [open, setOpen]       = useState("1");
  const [loading, setLoading] = useState(true);

  // Etats “userInfo” et valeurs éditables
  const [userInfo, setUserInfo]       = useState({
    pseudo: "",
    email: "",
    creationCompte: "",
    lastConnection: "",
    defisValides: []
  });
  const [pseudoValue,   setPseudo]    = useState("");
  const [emailValue,    setEmail]     = useState("");
  const [editPseudo,    setEditPseudo]= useState(false);
  const [editEmail,     setEditEmail] = useState(false);
  //mdp
  const [modalMdp, setModalMdp]           = useState(false);
  const [oldMdp,    setOldMdp]            = useState("");
  const [newMdp,    setNewMdp]            = useState("");
  const [confirmMdp,setConfirmMdp]        = useState("");
  // Récupère les infos user au montage
  useEffect(() => {
    props.setShowLeftNavigation(true);
    props.setShowLeaderboard(false);

    props.sendData({ route: "/get_info_user", method: "GET" })
      .then(json => {
        if (!json.error) {
          const d = json.data;
          const formatted = {
            pseudo:         d.pseudo,
            email:          d.email,
            creationCompte: new Date(d.creationCompte).toLocaleDateString(),
            lastConnection: new Date(d.lastConnection).toLocaleDateString(),
            defisValides:   d.defis_valide
          };
          setUserInfo(formatted);
          setPseudo(d.pseudo);
          setEmail(d.email);
        }
      })
      .finally(() => setLoading(false));
  }, []);

  // Handlers mise à jour API
  const savePseudo = () => {
    props.sendData({
      route: "change_username",
      method: "POST",
      data: { new_username: pseudoValue }
    }).then(res => {
      if (!res.error) {
        setUserInfo(u => ({ ...u, pseudo: pseudoValue }));
      } else {
        setPseudo(userInfo.pseudo);
      }
      setEditPseudo(false);
    });
  };
  const saveEmail = () => {
    props.sendData({
      route: "/change_email",
      method: "POST",
      data: {
        usermail:   userInfo.email,        // current email
        password:   oldMdp,                // you need old password
        new_email:  emailValue             // new_email
      }
    }).then(res => {
      if (!res.error) {
        setUserInfo(u => ({ ...u, email: emailValue }));
      } else {
        setEmail(userInfo.email);
      }
      setEditEmail(false);
    });
  };

  const saveMdp = () => {
      if (newMdp !== confirmMdp) {
        // TODO: show toast “Les mots de passe ne correspondent pas”
        return;
      }
       props.sendData({
         route: "change_password",
         method: "POST",
         data: {
          usermail:     userInfo.email,      // mail from info
          password:     oldMdp,              // old password
          new_password: newMdp               // new_password
        }
       }).then(res => {
         if (!res.error) {
           setModalMdp(false);
           setOldMdp("");
           setNewMdp("");
           setConfirmMdp("");
         }
       });
  };
  const toggle = id => setOpen(open === id ? undefined : id);

  return (
    <div
      className="h-100 w-100 d-flex flex-row justify-content-evenly"
      style={{ color: "white" }}
    >
      {/* Colonne de gauche – responsive et scrollable */}
      <div
        className="d-flex flex-column align-items-center gap-3 mt-5 overflow-auto vh-100"
        style={{ maxWidth: "300px", width: "100%" }}
      >
        {/* Avatar */}
        <Placeholder animation="glow">
          <Placeholder
            style={{ height: "110px", width: "110px", borderRadius: "80px" }}
          />
        </Placeholder>

        {/* Bonjour */}
        <div style={{ fontWeight: 600, fontSize: 20 }}>
          {loading ? "Bonjour…" : `Bonjour ${userInfo.pseudo}`}
        </div>

        {/* Titre Défis */}
        <div style={{ fontWeight: 700, color: "#a899e7", fontSize: 30 }}>
          Défis
        </div>

        {/* Accordion Score */}
        <div className="w-100">
          <Accordion open={open} toggle={toggle} className="w-100">
            <AccordionItem>
              <AccordionHeader targetId="1">
                <div className="d-flex flex-row w-100">
                  <div className="w-50" style={{ borderRight: "1px solid black" }}>
                    Score global :
                  </div>
                  <div className="d-flex w-50 justify-content-center">
                    {loading ? (
                      <Placeholder style={{ width: 80 }} animation="wave">
                        <Placeholder xs={3} />
                      </Placeholder>
                    ) : (
                      // calcul de la somme des points
                      userInfo.defisValides.reduce((total, d) => total + d.points, 0)
                    )}
                  </div>
                </div>
              </AccordionHeader>
              <AccordionBody accordionId="1">
                {loading ? (
                  "Chargement…"
                ) : userInfo.defisValides.length === 0 ? (
                  "Pas encore de défis validés"
                ) : (
                  userInfo.defisValides.map((d, i) => (
                    <div key={i} className="mb-2">
                      <strong>{d.nom}</strong> — {d.points} pts —{" "}
                      {new Date(d.dateValid).toLocaleDateString()}
                    </div>
                  ))
                )}
              </AccordionBody>
            </AccordionItem>
          </Accordion>
        </div>

        {/* Informations */}
        <h2
          className="transition"
          style={{ color: props.isDarkMode ? "white" : "black" }}
        >
          Informations
        </h2>

        {/* Pseudo éditable */}
        {editPseudo ? (
          <div className="d-flex align-items-center w-100 mb-2">
            <Input
              value={pseudoValue}
              onChange={e => setPseudo(e.target.value)}
              style={{ flex: 2, minWidth: 0, marginRight: 8 }}
            />
            <Button color="link" size="sm" onClick={savePseudo}>
              <i className="bi bi-check-lg" />
            </Button>
            <Button
              color="link"
              size="sm"
              onClick={() => {
                setPseudo(userInfo.pseudo);
                setEditPseudo(false);
              }}
            >
              <i className="bi bi-x-lg" />
            </Button>
          </div>
        ) : (
          <div className="d-flex align-items-center w-100 mb-2">
            <div style={{ flex: 2, minWidth: 0 }}>
              {loading ? "…" : userInfo.pseudo}
            </div>
            <Button color="link" size="sm" onClick={() => setEditPseudo(true)}>
              <i className="bi bi-pencil" />
            </Button>
          </div>
        )}

        {/* Email éditable */}
        {editEmail ? (
          <div className="d-flex align-items-center w-100 mb-2">
            <Input
              value={emailValue}
              onChange={e => setEmail(e.target.value)}
              style={{ flex: 2, minWidth: 0, marginRight: 8 }}
            />
            <Button color="link" size="sm" onClick={saveEmail}>
              <i className="bi bi-check-lg" />
            </Button>
            <Button
              color="link"
              size="sm"
              onClick={() => {
                setEmail(userInfo.email);
                setEditEmail(false);
              }}
            >
              <i className="bi bi-x-lg" />
            </Button>
          </div>
        ) : (
          <div className="d-flex align-items-center w-100 mb-2">
            <div style={{ flex: 2, minWidth: 0 }}>
              {loading ? "…" : userInfo.email}
            </div>
            <Button color="link" size="sm" onClick={() => setEditEmail(true)}>
              <i className="bi bi-pencil" />
            </Button>
          </div>
        )}

        {/* Dates en lecture seule */}
        <Input
          className="mb-2"
          value={loading ? "" : userInfo.creationCompte}
          disabled
          style={{ minWidth: 0 }}
        />
        <Input
          className="mb-4"
          value={loading ? "" : userInfo.lastConnection}
          disabled
          style={{ minWidth: 0 }}
        />

      <div className="d-flex align-items-center w-100 mb-2">
        {/* Ici on n’affiche pas le vrai mot de passe */}
        <div style={{ flex: 2, minWidth: 0 }}>
          ••••••••
        </div>
        {/* Le crayon ouvre ton modal de changement */}
        <Button color="link" size="sm" onClick={() => setModalMdp(true)}>
          <i className="bi bi-pencil" />
        </Button>
      </div>
        {/* Déconnexion */}
        <Button onClick={() => props.logout()}>Se Déconnecter</Button>
      </div>

       {/* ---------- MODAL CHANGE MD5 ---------- */}
     <Modal isOpen={modalMdp} toggle={() => setModalMdp(!modalMdp)}>
       <ModalHeader toggle={() => setModalMdp(false)}>
         Changer de mot de passe
       </ModalHeader>
       <ModalBody>
         <FormGroup>
           <Label for="oldMdp">Ancien mot de passe</Label>
           <Input
             id="oldMdp"
             type="password"
             value={oldMdp}
             onChange={e => setOldMdp(e.target.value)}
           />
         </FormGroup>
         <FormGroup>
           <Label for="newMdp">Nouveau mot de passe</Label>
           <Input
             id="newMdp"
             type="password"
             value={newMdp}
             onChange={e => setNewMdp(e.target.value)}
           />
         </FormGroup>
         <FormGroup>
           <Label for="confirmMdp">Confirmation</Label>
           <Input
             id="confirmMdp"
             type="password"
             value={confirmMdp}
             onChange={e => setConfirmMdp(e.target.value)}
           />
         </FormGroup>
       </ModalBody>
       <ModalFooter>
         <Button color="primary" onClick={saveMdp} disabled={!oldMdp || !newMdp || newMdp!==confirmMdp}>
           Valider
         </Button>
         <Button color="secondary" onClick={() => setModalMdp(false)}>
           Annuler
         </Button>
       </ModalFooter>
     </Modal>
    </div>
  );
}
