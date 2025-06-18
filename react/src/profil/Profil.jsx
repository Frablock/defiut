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
import CustomButton from "../utils/CustomButton";

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
  
  const [viewSize, setViewSize] = React.useState("0")
  const [pseudoValue,   setPseudo]    = useState("");
  const [emailValue,    setEmail]     = useState("");
  const [editPseudo,    setEditPseudo]= useState(false);
  const [editEmail,     setEditEmail] = useState(false);

  const [modalMdp, setModalMdp]           = useState(false);
  const [oldMdp,    setOldMdp]            = useState("");
  const [newMdp,    setNewMdp]            = useState("");
  const [confirmMdp,setConfirmMdp]        = useState("");

  React.useEffect(() => {
        if (!props.footerRef?.current || !props.navbarRef?.current) return;

        const calculateSize = () => {
            const footerHeight = props.footerRef.current.offsetHeight;
            const navbarHeight = props.navbarRef.current.offsetHeight;
            setViewSize(footerHeight + navbarHeight);
        };

        calculateSize();

        const resizeObserver = new ResizeObserver(calculateSize);
        resizeObserver.observe(props.footerRef.current);
        resizeObserver.observe(props.navbarRef.current);

        return () => resizeObserver.disconnect();
    }, [props.footerRef?.current, props.navbarRef?.current]);

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
            creationCompte: new Date(d.creationCompte.date).toLocaleDateString(),
            lastConnection: new Date(d.lastConnection.date).toLocaleDateString(),
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
    <div className="w-100 pt-">
        <div
        className="d-flex flex-row align-items-center justify-content-evenly overflow-scroll"
        style={{ 
          color: "white",
          height:`calc(100vh - ${10+viewSize}px)`,
        }}
      >
        <div
          className=" mx-2 d-flex flex-column align-items-center gap-3 mt-5 overflow-auto"
          style={{
          minWidth:"600px"}}
        >

          {/* Bonjour */}
          <div className="transition" style={{ fontWeight: 600, fontSize: 20, color: props.isDarkMode? "white" :"black" }}>
            {loading ? "Bonjour…" : `Bonjour ${userInfo.pseudo}`}
          </div>

          {/* Titre Défis */}
          <div style={{ fontWeight: 700, color: "#a899e7", fontSize: 30 }}>
            Défis
          </div>

          {/* Accordion Score */}
          <div className="w-100">
            <Accordion open={open} toggle={toggle} className="w-100 shadow">
              <AccordionItem style={{color:"white"}}>
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
                <AccordionBody accordionId="1" style={{backgroundColor:"#535353"}}>
                  {loading ? (
                    "Chargement…"
                  ) : userInfo.defisValides.length === 0 ? (
                    "Pas encore de défis validés"
                  ) : (
                    userInfo.defisValides.map((d, i) => (
                      <div key={i} className="mb-2 d-flex flex-row justify-content-around">
                        <div className="w-100 text-center">
                          {d.nom}
                        </div>
                        <div className="w-100 text-center">
                          — 
                        </div>
                        <div className="w-100 text-center">
                          {d.points}pts
                        </div>
                        <div className="w-100 text-center">
                          —
                        </div >
                        <div className="w-100 text-center">
                          {new Date(d.dateValid.date).toLocaleDateString()}
                        </div>
                      </div>
                    ))
                  )}
                </AccordionBody>
              </AccordionItem>
            </Accordion>
          </div>

          <div style={{ fontWeight: 700, color: "#a899e7", fontSize: 30 }}>
            Informations
          </div>

          {/* Pseudo éditable */}
          <div className="transition" style={{color: props.isDarkMode?"white":"black"}}>Pseudo</div>
          {editPseudo ? (
            <div className="d-flex align-items-center w-100 mb-2 gap-2">
              <Input
                value={pseudoValue}
                onChange={e => setPseudo(e.target.value)}
                style={{ flex: 2, minWidth: 0, marginRight: 8 }}
              />
              <CustomButton color="link" size="sm" onClick={savePseudo}>
                <i className="bi bi-check-lg" />
              </CustomButton>
              <CustomButton
                color="link"
                size="sm"
                onClick={() => {
                  setPseudo(userInfo.pseudo);
                  setEditPseudo(false);
                }}
              >
                <i className="bi bi-x-lg" />
              </CustomButton>
            </div>
          ) : (
            <div className="d-flex align-items-center w-100 mb-2 p-2 transition shadow" style={{backgroundColor: props.isDarkMode? "#535353" : "#e2ddf7", borderRadius:"25px"}}>
              <div className="transition" style={{ flex: 2, minWidth: 0, color : props.isDarkMode ? "white":"black" }}>
                {loading ? "…" : userInfo.pseudo}
              </div>
              <Button color="link" size="sm" onClick={() => setEditPseudo(true)}>
                <i className="bi bi-pencil" />
              </Button>
            </div>
          )}

          <div className="transition" style={{color: props.isDarkMode?"white":"black"}}>Email</div>
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
            <div className="d-flex align-items-center w-100 mb-2 p-2 transition shadow" style={{backgroundColor: props.isDarkMode? "#535353" : "#e2ddf7", borderRadius:"25px"}}>
              <div className="transition" style={{ flex: 2, minWidth: 0 , color : props.isDarkMode ? "white":"black" }}>
                {loading ? "…" : userInfo.email}
              </div>
              <Button color="link" size="sm" onClick={() => setEditEmail(true)}>
                <i className="bi bi-pencil" />
              </Button>
            </div>
          )}


          <div className="transition" style={{color: props.isDarkMode?"white":"black"}}>Date de création du compte</div>
          <Input
            className="mb-2"
            value={loading ? "" : userInfo.creationCompte}
            disabled
            style={{ minWidth: 0 }}
          />

          <div className="transition" style={{color: props.isDarkMode?"white":"black"}}>Dernière connexion</div>
          <Input
            className="mb-4"
            value={loading ? "" : userInfo.lastConnection}
            disabled
            style={{ minWidth: 0 }}
          />


        <div className="transition" style={{color: props.isDarkMode?"white":"black"}}>Changer de mot de passe</div>
        <div className="d-flex align-items-center w-100 mb-2 justify-content-center">
          <Button size="sm" onClick={() => setModalMdp(true)}>
          <div className=" w-100 gap-3 d-flex flex-row justify-content-center align-items-center">
            <div className="w-100">
              •••••••• 
            </div>
            <i className="bi bi-pencil" />
          </div>
          </Button>
          </div>
            <Button onClick={() => props.logout()}>Se Déconnecter</Button>
          </div>

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
    </div>
  );
}
