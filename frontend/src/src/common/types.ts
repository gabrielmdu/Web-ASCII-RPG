export interface User {
  name: string;
  email: string;
  isVerified: boolean;
  activeSessions: GameSession[];
}

export interface Creator {
  id: number;
  name: string;
}

export interface Game {
  creator?: Creator;
  name: string;
  slug: string;
  isPublic: boolean;
  description: string;
  version: string;
  createdAt: string;
  lastModified: string;
  settings: Record<string, unknown> | null;
  sessions: GameSession[];
  scenesCount?: number;
  scenesUrl: string;
}

export interface Scene {
  id: number;
  choices: Choice[];
  title: string;
  media: string;
  text: string;
  type: string;
}

export interface Choice {
  id: number;
  text: string;
}

export interface GameSession {
  id: number;
  player?: User;
  game?: Game;
  status: GameSessionStatus;
  currentScene?: Scene;
  settings: Record<string, unknown> | null;
  history: Record<string, unknown> | null;
  createdAt: string;
  updatedAt: string;
}

export enum GameSessionStatus {
  ACTIVE = 'active',
  FINISHED = 'finished',
  ABANDONED = 'abandoned',
}
