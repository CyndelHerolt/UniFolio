#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

CONSOLE="php bin/console"

DRY_RUN=true
if [[ "${1:-}" == "--force" ]]; then
  DRY_RUN=false
fi

echo "Recherche des entrées orphelines (sans User lié)..."

echo
echo "Étudiants sans User :"
$CONSOLE doctrine:query:dql "SELECT e.id, e.prenom, e.nom, e.mail_univ FROM App\\Entity\\Etudiant e LEFT JOIN App\\Entity\\Users u WITH u.etudiant = e WHERE u.id IS NULL" || true

echo
echo "Personnels (Enseignants) sans User :"
$CONSOLE doctrine:query:dql "SELECT p.id, p.prenom, p.nom, p.mail_univ FROM App\\Entity\\Enseignant p LEFT JOIN App\\Entity\\Users u WITH u.enseignant = p WHERE u.id IS NULL" || true

if [[ "$DRY_RUN" == true ]]; then
  echo
  echo "Mode prévisualisation actif : aucune suppression effectuée."
  echo "Relancez avec --force pour supprimer les entrées affichées."
  exit 0
fi

echo
echo "Suppression des Étudiants sans User..."
$CONSOLE doctrine:query:dql "DELETE FROM App\\Entity\\Etudiant e WHERE NOT EXISTS (SELECT u.id FROM App\\Entity\\Users u WHERE u.etudiant = e)"

echo "Suppression des Personnels (Enseignants) sans User..."
$CONSOLE doctrine:query:dql "DELETE FROM App\\Entity\\Enseignant p WHERE NOT EXISTS (SELECT u.id FROM App\\Entity\\Users u WHERE u.enseignant = p)"

echo
echo "Suppression terminée."
