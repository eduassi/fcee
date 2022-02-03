/*   
  INSTRUÇÕES:

  Altere abaixo as propriedades para cada link do MOODLE/LMS:

  "url"           = Link para redirecionamento quando o botão é clicado
  "hidden"        = 'true' inativar o botão. Remover a propriedade ou atribuir 'false' para habilitar.
  "new-tab"       = 'true' para abrir o URL em nova aba. Remover a propriedade ou atribuir 'false' para abrir o URL na mesma aba.

*/

var links_JSON = {
  ".menu-do-topo": {
    ".inicie-o-curso": {
      url: "https://grupos.moodle.ufsc.br/course/view.php?id=2578",
      "new-tab": true,
    },
    ".apresentacao-do-curso": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=133916",
      "new-tab": true,
    },
    ".guia-de-estudo": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134903",
      "new-tab": true,
    },
    ".conteudo-completo": {
      url: "",
      hidden: true,
    },
    ".certificado": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134913",
      "new-tab": true,
      hidden: true,
    },
  },
  ".modulo-1": {
    apresentacao: {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134902/#0",
      placeholder: "Apresentação",      
    },
    unidade1: {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134902/#1",
      placeholder: "Unidade 1",
    },
    unidade2: {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134902/#2",
      placeholder: "Unidade 2",
    },
    referencias: {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134902/#3",
      placeholder: "Referências",
    },
    avaliacao: {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134904",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
  ".modulo-2": {
    "apresentacao": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134896/#0",
      placeholder: "Apresentação",
    },
    "unidade-1": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134896/#1",
      placeholder: "Unidade 1",
    },
    "unidade-2": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134896/#2",
      placeholder: "Unidade 2",
    },
    "referencias": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134896/#3",
      placeholder: "Referências",
    },
    "avaliacao": {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134905",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
  ".modulo-3": {
    "apresentacao": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134897",
      placeholder: "Apresentação",
    },
    "unidade-1": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134897/1",
      placeholder: "Unidade 1",
    },
    "unidade-2": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134897/2",
      placeholder: "Unidade 2",
    },
    "unidade-3": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134897/3",
      placeholder: "Unidade 3",
    },
    "unidade-4": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134897/4",
      placeholder: "Unidade 4",
    },
    "referencias": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134897/5",
      placeholder: "Referências",
    },
    "avaliacao": {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134906",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
  ".modulo-4": {
    "apresentacao": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134898",
      placeholder: "Apresentação",
    },
    "unidade-1": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134898/#1",
      placeholder: "Unidade 1",
    },
    "unidade-2": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134898/#2",
      placeholder: "Unidade 2",
    },
    "unidade-3": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134898/#3",
      placeholder: "Unidade 3",
    },
    "referencias": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134898/#4",
      placeholder: "Referências",
    },
    "avaliacao": {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134907",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
  ".modulo-5": {
    "apresentacao": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134899",
      placeholder: "Apresentação",
    },
    "unidade-1": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134899/#1",
      placeholder: "Unidade 1",
    },
    "unidade-2": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134899/#2",
      placeholder: "Unidade 2",
    },
    "unidade-3": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134899/#3",
      placeholder: "Unidade 3",
    },
    "unidade-4": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134899/#4",
      placeholder: "Unidade 4",
    },
    "referencias": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134899/#5",
      placeholder: "Referências",
    },
    "avaliacao": {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134908",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
  ".modulo-6": {
    "apresentacao": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134900/#0",
      placeholder: "Apresentação",
    },
    "unidade-1": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134900/#1",
      placeholder: "Unidade 1",
    },
    "unidade-2": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134900/#2",
      placeholder: "Unidade 2",
    },
    "referencias": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134900/#3",
      placeholder: "Referências",
    },
    "avaliacao": {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134909",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
  ".modulo-7": {
    "apresentacao": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134901/#0",
      placeholder: "Apresentação",
    },
    "unidade-1": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134901/#1",
      placeholder: "Unidade 1",
    },
    "unidade-2": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134901/#2",
      placeholder: "Unidade 2",
    },
    "referencias": {
      url: "https://grupos.moodle.ufsc.br/mod/resource/view.php?id=134901/#3",
      placeholder: "Referências",
    },
    "avaliacao": {
      url: "https://grupos.moodle.ufsc.br/mod/quiz/view.php?id=134910",
      placeholder: "Avaliação",
      "new-tab": true,
    },
  },
};
